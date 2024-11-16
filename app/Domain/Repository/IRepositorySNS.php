<?php

namespace App\Domain\Repository;

use App\Domain\Entity\News;
use App\Domain\Entity\SNSPayload;

interface IRepositorySNS
{
    public function setUrl(string $url): void;
    public function postNews(News $news): string;
    public function postSNSPayload(SNSPayload $payload): string;
}