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


class NewsController extends AbstractController
{
    private NewsServiceInterface $news;

    public function __construct(NewsServiceInterface $news)
    {
        $this->news = $news;
    }


    #[Route('/news', name: 'show_all_news')]
    public function index(Request $request): Response
    {
        $pageSize = $this->getParameter('page_size');
        $offset = $request->query->getInt('page', 1);

        return $this->render('news/index.html.twig', [
            'pagination' => $this->news->getPaginator($offset, $pageSize),

        ]);
    }


    #[Route('/news/{id}', name: 'show_news')]
    public function show(int $id): Response
    {
        $news = $this->news->show($id);

        return $this->render('news/show.html.twig', [
            'news' => $news,
        ]);
    }
}
