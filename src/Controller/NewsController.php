<?php

namespace App\Controller;

use App\Entity\News;
use App\Services\NewsServiceInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Knp\Component\Pager\PaginatorInterface;

class NewsController extends AbstractController
{
    private NewsServiceInterface $news;

    public function __construct(NewsServiceInterface $news)
    {
        $this->news = $news;
    }

    #[Route('/news/add', name: 'add_news')]
    public function addNews(ManagerRegistry $doctrine, ValidatorInterface $validator): Response
    {
        $entityManager = $doctrine->getManager();

        $news = new News();
        $news->setTitle('Заголовок новости');
        $news->setPreview('Анонс новости');
        $news->setText('Текст новости');
        $news->setDate(new \DateTime('now'));
        $news->setCounter(0);

        $errors = $validator->validate($news);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }

        $entityManager->persist($news);
        $entityManager->flush();

        return new Response('Saved new news with id ' . $news->getId());
    }


    #[Route('/news', name: 'show-all-news')]
    public function index(Request $request): Response
    {
        $pageSize = $this->getParameter('page_size');
        $offset = $request->query->getInt('page', 1);

        return $this->render('news/index.html.twig', [
            'pagination' => $this->news->getPaginator($offset, $pageSize),
        ]);
    }


    #[Route('/news/{id}', name: 'show-news')]
    public function show(int $id): Response
    {
        $news = $this->news->show($id);

        return $this->render('news/show.html.twig', ['news' => $news]);
    }
}
