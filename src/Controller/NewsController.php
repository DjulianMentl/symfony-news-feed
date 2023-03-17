<?php

namespace App\Controller;

use App\Entity\News;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class NewsController extends AbstractController
{
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

    #[Route('/news', name: 'app_news')]
    public function index(): Response
    {
        return $this->render('news/index.html.twig', [
            'controller_name' => 'NewsController',
        ]);
    }
}
