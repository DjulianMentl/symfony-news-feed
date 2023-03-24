<?php

namespace App\Services;


use App\Entity\News;
use App\Repository\NewsRepository;
use App\Exception\ExceptionMessages;
use Exception;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class NewsService implements NewsServiceInterface
{
    private NewsRepository $newsRepository;
    private PaginatorInterface $paginator;
    private FileUploaderInterface $fileUploader;

    public function __construct(NewsRepository $newsRepository, PaginatorInterface $paginator, FileUploaderInterface $fileUploader)
    {
        $this->newsRepository = $newsRepository;
        $this->paginator = $paginator;
        $this->fileUploader = $fileUploader;
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

    public function save(News $news, Form $form = null, string $imagePathDir = ''): void
    {
        try {
            isset($form) ? $image = $form['image']->getData(): $image = null;
            if ($image) {
                $fileName = $this->fileUploader->upload($image, $imagePathDir);
                $news->setImage($fileName);
            }

            $this->newsRepository->save($news, true);
        } catch (Exception $e) {
            throw new NotFoundHttpException(ExceptionMessages::NEWS_SAVE_ERROR_MESSAGE);
        }

    }
}