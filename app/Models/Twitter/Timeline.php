<?php

namespace App\Models\Twitter;

class Timeline extends LeagueOAuthClient
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
		$result = json_decode($this->request('statuses/home_timeline.json','GET',$params), true);

		return $result;
	}
}
