<?php

namespace App\Domain\Repository;

use App\Domain\Entity\News;

interface IRepositorySentNews
{
    public function setKind(string $kd): void;
    public function getAll(): array;
    public function insertNews(News $news): string;
    public function deleteAllThatBefore(int $oldest_timestamp): array;
}