<?php

namespace App\Events;

use App\Entity\News;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

class NewsViewedEvent extends Event
{
    public const NAME = 'news.viewed';

    public News $news;
    public Response $response;

    public function __construct(News $news, Response $response)
    {
        $this->news = $news;
        $this->response = $response;
    }
}