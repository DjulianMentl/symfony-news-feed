<?php

namespace App\Services;


use App\Entity\News;
use App\Repository\NewsRepository;
use App\Exception\ExceptionMessages;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class NewsService implements NewsServiceInterface
{
    private NewsRepository $newsRepository;
    private PaginatorInterface $paginator;

    public function __construct(NewsRepository $newsRepository, PaginatorInterface $paginator)
    {
        $this->newsRepository = $newsRepository;
        $this->paginator = $paginator;
    }


    public function getAll(): array
    {
        $allNews = $this->newsRepository->findAll();

        if (!$allNews) {
            throw new NotFoundHttpException(ExceptionMessages::NEWS_OUTPUT_ERROR_MESSAGE);
        }

        return $allNews;
    }


    public function getPaginator(int $offset, int $pageSize): PaginationInterface
    {
        $this->getOffset($offset, $pageSize);

        return $this->paginator->paginate(
            $this->newsRepository->getQuery(),
            $offset,
            $pageSize,
        );
    }


    private function getOffset(int $offset, int $pageSize)
    {
        $count = count($this->getAll());

        if ($offset > $count / $pageSize) {
            throw new NotFoundHttpException(ExceptionMessages::NEWS_OUTPUT_ERROR_MESSAGE);
        }
    }

    public function show(int $id): News
    {
        $news = $this->newsRepository->find($id);

        if (!$news) {
            throw new NotFoundHttpException(ExceptionMessages::NEWS_OUTPUT_ERROR_MESSAGE);
        }

        return $news;
    }
}