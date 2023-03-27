<?php

namespace App\Controller;

use App\Events\NewsViewedEvent;
use App\Services\NewsServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function show(EventDispatcherInterface $dispatcher, Request $request, int $id): Response
    {
        $news = $this->news->show($id);

        $response = $this->render('news/show.html.twig', [
            'news' => $news,
        ]);

        if (!$request->cookies->has('counter_' . $id)) {
            $event = new NewsViewedEvent($news, $response);
            $dispatcher->dispatch($event,NewsViewedEvent::NAME);
        }

        return $response;
    }
}
