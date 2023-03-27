<?php

namespace App\Components;

use App\Services\NewsService;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('last_news_date')]
class LastNewsDateComponent
{
    public string $lastNewsDate;

    public function __construct(NewsService $newsService)
    {
        $this->lastNewsDate = $newsService->getLastNewsDate();
    }
}