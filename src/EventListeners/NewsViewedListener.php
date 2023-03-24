<?php

namespace App\EventListeners;

use App\Events\NewsViewedEvent;
use App\Repository\NewsRepository;
use App\Services\EmailNewsRatingNotification;
use App\Services\NewsServiceInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NewsViewedListener
{
    private NewsServiceInterface $news;
    private EmailNewsRatingNotification $newsRatingNotification;

    public function __construct(NewsServiceInterface $news, EmailNewsRatingNotification $newsRatingNotification)
    {
        $this->news = $news;
        $this->newsRatingNotification = $newsRatingNotification;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function onNewsViewed(NewsViewedEvent $event): void
    {
        $cookie = Cookie::create('counter_' . $event->news->getId(), true, time() + (30 * 60));
        $event->response->headers->setCookie($cookie);

        $event->news->setCounter($event->news->getCounter() + 1);
        $this->news->save($event->news);

        if ($event->news->getCounter() % 10 == 0) {
            $this->newsRatingNotification->send($event->news->getTitle(), $event->news->getCounter());
        }
    }
}