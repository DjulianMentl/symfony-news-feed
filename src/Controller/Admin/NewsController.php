<?php

namespace App\Controller\Admin;

use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use App\Services\NewsServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class NewsController extends AbstractController
{
    private NewsServiceInterface $news;

    public function __construct(NewsServiceInterface $news)
    {
        $this->news = $news;
    }


    #[Route('/news', name: 'admin_show_all_news', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $pageSize = $this->getParameter('page_size');
        $offset = $request->query->getInt('page', 1);

        return $this->render('news/admin/index.html.twig', [
            'pagination' => $this->news->getPaginator($offset, $pageSize),
            'imagesDirectory' => $this->getParameter('images_directory'),
        ]);
    }


    #[Route('/news/create', name: 'news_create', methods: ['GET'])]
    public function create(): Response
    {
        return $this->render('news/admin/create.html.twig', [
            'form' => $this->createForm(NewsType::class),
        ]);
    }

    #[Route('/news', name: 'news_store', methods: ['POST'])]
    public function store(Request $request): Response
    {
        $news = new News();
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return $this->render('news/admin/create.html.twig', [
                'form' => $form,
            ]);
        }

        $this->news->save($news, $form, $this->getParameter('images_directory'));

        return $this->redirectToRoute('admin_show_all_news', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/news/{id}/edit', name: 'news_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, News $news): Response
    {
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($request->get('del-img')) {
                $news->setImage(null);
            }

            $this->news->save($news, $form, $this->getParameter('images_directory'));

            return $this->show($news);
        }

        return $this->render('news/admin/edit.html.twig', [
            'news' => $news,
            'form' => $form,
        ]);
    }


    #[Route('/news/{id}', name: 'news_delete', methods: ['DELETE'])]
    public function delete(Request $request, News $news, NewsRepository $newsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$news->getId(), $request->request->get('_token'))) {
            $newsRepository->remove($news, true);
        }

        return $this->redirectToRoute('admin_show_all_news', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/news/{id}', name: 'admin_show_news', methods: ['GET'])]
    public function show(News $news): Response
    {
        return $this->render('news/admin/show.html.twig', [
            'news' => $news,
        ]);
    }
}
