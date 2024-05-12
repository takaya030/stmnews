<?php

return [

	/*
	|--------------------------------------------------------------------------
	| oAuth Accounts
	|--------------------------------------------------------------------------
	*/

	/**
	 * Slack
	 */
	'slack' => [
		'url'		=> env('SLACK_URL'),
		'game_url'	=> env('GAME_SLACK_URL'),
	],

	/**
	 * Twitter
	 */
	'twitter' => [
		//'client_id'		=> env('TWITTER_CLIENT_ID'),
		//'client_secret'	=> env('TWITTER_CLIENT_SECRET'),
		//'access_token'	=> env('TWITTER_ACCESS_TOKEN'),
		//'access_token_secret'	=> env('TWITTER_ACCESS_TOKEN_SECRET'),
		'user_id'		=> env('TWITTER_USER_ID'),
	],

	/**
	 * Google
	 */
	'google' => [
		'datastore_kind'	=> env('DATASTORE_KIND'),
		'game_datastore_kind'	=> env('GAME_DATASTORE_KIND'),
	],

	/**
	 * RSS
	 */
	'rss' => [
		'url'	=> env('RSS_URL'),
		'game_url'	=> env('GAME_RSS_URL'),
	],

];
