<?php

namespace App\Controller\Admin;

use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use App\Services\FileUploader;
use App\Services\FileUploaderInterface;
use App\Services\NewsServiceInterface;
use Exception;
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


    #[Route('/news', name: 'admin_show_all_news')]
    public function index(Request $request): Response
    {
        $pageSize = $this->getParameter('page_size');
        $offset = $request->query->getInt('page', 1);

        return $this->render('news/admin/index.html.twig', [
            'pagination' => $this->news->getPaginator($offset, $pageSize),
            'imagesDirectory' => $this->getParameter('images_directory'),
        ]);
    }


    /**
     * @throws Exception
     */
    #[Route('/news/new', name: 'app_news_new', methods: ['GET', 'POST'])]
    public function new(Request $request, NewsRepository $newsRepository, FileUploaderInterface $fileUploader): Response
    {
        $news = new News();
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form['image']->getData();

            if ($image) {
                $fileName = $fileUploader->upload($image, $this->getParameter('images_directory'));
                $news->setImage($fileName);
            }

            $newsRepository->save($news, true);

            return $this->redirectToRoute('admin_show_all_news', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('news/admin/new.html.twig', [
            'news' => $news,
            'form' => $form,
        ]);
    }

    #[Route('/news/{id}', name: 'admin_show_news', methods: ['GET'])]
    public function show(News $news): Response
    {
        return $this->render('news/show.html.twig', [
            'news' => $news,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_news_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, News $news, NewsRepository $newsRepository): Response
    {
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newsRepository->save($news, true);

            return $this->redirectToRoute('app_news_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('news/edit.html.twig', [
            'news' => $news,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_news_delete', methods: ['POST'])]
    public function delete(Request $request, News $news, NewsRepository $newsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$news->getId(), $request->request->get('_token'))) {
            $newsRepository->remove($news, true);
        }

        return $this->redirectToRoute('app_news_index', [], Response::HTTP_SEE_OTHER);
    }
}
