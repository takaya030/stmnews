<?php

namespace App\Domain\Repository;

use App\Domain\Entity\News;

interface IRepositorySNS
{
    public function postNews(News $news);
}