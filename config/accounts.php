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
		'blog_url'	=> env('BLOG_SLACK_URL'),
	],

	/**
	 * Twitter
	 */
	'twitter' => [
		'user_id'		=> env('TWITTER_USER_ID'),
	],

	/**
	 * Google
	 */
	'google' => [
		'datastore_kind'	=> env('DATASTORE_KIND'),
		'game_datastore_kind'	=> env('GAME_DATASTORE_KIND'),
		'aws_datastore_kind'	=> env('AWS_DATASTORE_KIND'),
	],

	/**
	 * RSS
	 */
	'rss' => [
		'url'	=> env('RSS_URL'),
		'game_url'	=> env('GAME_RSS_URL'),
		'aws_url'	=> env('AWS_RSS_URL'),
	],

];
