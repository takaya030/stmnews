<?php

namespace App\Domain\Repository;

use App\Domain\Entity\News;

interface IRepositorySNS
{
    public function setUrl(string $url): void;
    public function postNews(News $news): string;
}