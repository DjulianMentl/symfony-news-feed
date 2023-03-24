<?php

namespace App\EventListeners;

use App\Events\NewsViewedEvent;
use App\Repository\NewsRepository;
use App\Services\NewsServiceInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class NewsViewedListener
{
    private $news;

    public function __construct(NewsServiceInterface $news)
    {
        $this->news = $news;
    }

    public function onNewsViewed(NewsViewedEvent $event)
    {
        $cookie = Cookie::create('counter_' . $event->news->getId(), true, time() + (10 * 60));
        $event->response->headers->setCookie($cookie);

        $event->news->setCounter($event->news->getCounter() + 1);
        $this->news->save($event->news);


    }
}