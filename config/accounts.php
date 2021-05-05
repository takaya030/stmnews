<?php

return [

	/*
	|--------------------------------------------------------------------------
	| oAuth Accounts
	|--------------------------------------------------------------------------
	*/

	/**
	 * Twitter
	 */
	'twitter' => [
		'client_id'		=> env('TWITTER_CLIENT_ID'),
		'client_secret'	=> env('TWITTER_CLIENT_SECRET'),
		'access_token'	=> env('TWITTER_ACCESS_TOKEN'),
		'access_token_secret'	=> env('TWITTER_ACCESS_TOKEN_SECRET'),
		'user_id'		=> env('TWITTER_USER_ID'),

		'uma_client_id'		=> env('UMAMUSU_CLIENT_ID'),
		'uma_client_secret'	=> env('UMAMUSU_CLIENT_SECRET'),
		'uma_access_token'	=> env('UMAMUSU_ACCESS_TOKEN'),
		'uma_access_token_secret'	=> env('UMAMUSU_ACCESS_TOKEN_SECRET'),
		'uma_user_id'		=> env('UMAMUSU_USER_ID'),
	],

	/**
	 * Google
	 */
	'google' => [
		'client_id'		=> env('GOOGLE_CLIENT_ID'),
		'client_secret'	=> env('GOOGLE_CLIENT_SECRET'),
		'access_token'	=> env('GOOGLE_ACCESS_TOKEN'),
		'refresh_token'	=> env('GOOGLE_REFRESH_TOKEN'),

		'project_id'	=> env('GOOGLE_PROJECT_ID'),
		'datastore_kind'	=> env('DATASTORE_KIND'),
		'uma_datastore_kind'	=> env('UMA_DATASTORE_KIND'),
		'key_file'		=> env('GOOGLE_KEY_FILE'),
	],

	/**
	 * RSS
	 */
	'rss' => [
		'url'	=> env('RSS_URL'),
		'uma_url'	=> env('UMA_RSS_URL'),
	],

];
