<?php

namespace App\Models\Twitter;

use App\Models\Google\News\Item as NewsItem;

class Tweet extends OAuthClient
{
	public function __construct()
	{
		parent::__construct();
	}

	public function postNewsItem( NewsItem $news )
	{
		return $this->postText( $news->getTitle() . ' ' . $news->getUrl() );
	}

	public function postText( string $text )
	{
		$params = 'status=' . urlencode($text);

		// Send a request with it
		$result = json_decode( $this->service->request('https://api.twitter.com/1.1/statuses/update.json?'.$params, 'POST'), true);

		return $result;
	}
}
