<?php

namespace App\Models\Slack;

use App\Models\Google\News\Item as NewsItem;
use Throwable;

class Post
{
	protected $client;
	protected $url;

	public function __construct(string $url)
	{
		$this->client = new \GuzzleHttp\Client();
		$this->url = $url;
	}

	public function postNewsItem( NewsItem $news )
	{
		return $this->postText( $news->getTitle() . "\n" . $news->getUrl() );
	}

	public function postText( string $text )
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
