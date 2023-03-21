<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileUploaderInterface
{
    public function upload(UploadedFile $file,  string $targetDirectory): string;
}