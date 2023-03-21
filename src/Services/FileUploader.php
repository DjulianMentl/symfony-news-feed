<?php

namespace App\Services;

use Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader implements FileUploaderInterface
{
    /**
     * @throws Exception
     */
    public function upload(UploadedFile $file, string $targetDirectory): string
    {
        $fileName = uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($targetDirectory, $fileName);
        } catch (FileException $e) {
            throw new Exception('Ошибка при сохранении файла ' . $e);
        }

        return $fileName;
    }
}