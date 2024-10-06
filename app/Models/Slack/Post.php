<?php

namespace App\Models\Slack;

use App\Models\Google\News\Item as NewsItem;
use Throwable;

class Post
{
	protected $client;
	protected $url;

	/**
	 * @param string $url
	 */
	public function __construct(string $url)
	{
		$this->client = new \GuzzleHttp\Client();
		$this->url = $url;
	}

	/**
	 * @param App\Models\Goolge\News\NewsItem $news
	 * @return string
	 */
	public function postNewsItem(NewsItem $news): string
	{
		return $this->postText( $news->getTitle() . "\n" . $news->getUrl() );
	}

	/**
	 * @param App\Models\Slacke\Payload $news
	 * @return string
	 */
	public function postPayload(Payload $news): string
	{
		return $this->postText( $news->getTitle() . "\n" . $news->getUrl() );
	}

	/**
	 * @param string $text
	 * @return string
	 */
	public function postText(string $text): string
	{
        $method = 'POST';
        $url = $this->url;
        $options = [
            'json' => ['text' => $text],
        ];

		try {
			$response = $this->client->request($method,$url,$options);
			return $response->getBody()->getContents();
		}
		catch(Throwable $e)
		{
			throw $e;
		}
	}
}
