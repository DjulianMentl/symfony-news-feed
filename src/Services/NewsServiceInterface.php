<?php

namespace App\Services;

interface NewsServiceInterface
{
    public function getAll();
    public function show(int $id);
}