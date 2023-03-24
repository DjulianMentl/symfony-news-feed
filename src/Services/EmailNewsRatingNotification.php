<?php

namespace App\Services;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailNewsRatingNotification
{
    private MailerInterface $mailer;
    private ParameterBagInterface $params;

    public function __construct(MailerInterface $mailer, ParameterBagInterface $params)
    {
        $this->mailer = $mailer;
        $this->params = $params;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function send(string $title, int $counter): void
    {
        $textMail = 'Новость ' . $title . ' посмотрели ' . $counter . ' раз.';

        $email = (new Email())
            ->from($this->params->get('sender_email'))
            ->to($this->params->get('admin_email'))
            ->subject('Рейтинг просмотра новостей')
            ->text($textMail);

        $this->mailer->send($email);
    }
}