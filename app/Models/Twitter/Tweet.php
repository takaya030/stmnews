<?php

namespace App\Models\Twitter;

class Tweet extends OAuthClient
{
	public function __construct()
	{
		parent::__construct();
	}

	public function postText( string $text )
	{
		$params = 'status=' . urlencode($text);

		// Send a request with it
		$result = json_decode( $this->service->request('https://api.twitter.com/1.1/statuses/update.json?'.$params, 'POST'), true);

		return $result;
	}
}
