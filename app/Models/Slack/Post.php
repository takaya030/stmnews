<?php

namespace App\Models\Slack;

use App\Models\Google\News\Item as NewsItem;
use Throwable;

class Post
{
	protected $client;

	public function __construct()
	{
		$this->client = new \GuzzleHttp\Client();
	}

	public function postNewsItem( NewsItem $news )
	{
		return $this->postText( $news->getTitle() . "\n" . $news->getUrl() );
	}

	public function postText( string $text )
	{
        $method = 'POST';
        $url = env("SLACK_URL");
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
