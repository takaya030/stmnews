<?php

namespace App\Models\Twitter;

class Timeline extends OAuthClient
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getTimeline()
	{
		$params = [
			'count' => '5',
			//'trim_user' => true,
			'exclude_replies' => 'true',
			'tweet_mode' => 'extended',
		];

		// Send a request with it
		$result = json_decode($this->service->request('https://api.twitter.com/1.1/statuses/home_timeline.json?'.http_build_query($params),'GET'), true);

		return $result;
	}
}
