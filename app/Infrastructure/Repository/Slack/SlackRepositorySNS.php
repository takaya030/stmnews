<?php

namespace App\Infrastructure\Repository\Slack;

use App\Domain\Entity\News;
use App\Domain\Entity\SNSPayload;
use App\Domain\Repository\IRepositorySNS;

class SlackRepositorySNS extends SlackRepository implements IRepositorySNS
{
    /**
	   * @param string $url
	   * @return void
	   */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

   /**
	  * @param App\Domain\Entity\News $news
	  * @return string
	  */
    public function postNews(News $news): string
    {
        return $this->postText( $news->getTitle() . "\n" . $news->getUrl() );
    }

   /**
	  * @param App\Domain\Entity\SNSPayload $payload
	  * @return string
	  */
    public function postSNSPayload(SNSPayload $payload): string
    {
        return $this->postText( $payload->getTitle() . "\n" . $payload->getUrl() );
    }
}
