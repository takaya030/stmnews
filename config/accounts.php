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
	],

];
