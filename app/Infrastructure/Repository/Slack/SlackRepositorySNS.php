<?php

namespace App\Infrastructure\Repository\Slack;

use App\Domain\Entity\News;
use App\Domain\Repository\IRepositorySNS;

class SlackRepositorySNS extends SlackRepository implements IRepositorySNS
{
    public function postNews(News $news): string
    {
		return $this->postText( $news->getTitle() . "\n" . $news->getUrl() );
    }
}
