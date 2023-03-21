<?php

namespace App\Services;

use App\Repository\NewsRepository;

interface NewsServiceInterface
{
    public function getAll();
    public function show(int $id);
}