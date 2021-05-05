<?php

use \App\OAuth\OAuthLaravelSession;

return [

	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	//'storage' => '\\OAuth\\Common\\Storage\\Session',
	'storage' => '\\Takaya030\\OAuth\\OAuthLumenSession',

	/**
	 * Consumers
	 */
	'consumers' => [

		'Twitter' => [
			'client_id'     => env('TWITTER_CLIENT_ID'),
			'client_secret' => env('TWITTER_CLIENT_SECRET'),
			//'scope'         => [],				// No scope - oauth1 doesn't need scope
		],
		'UmaTwitter' => [
			'client_id'     => env('UMAMUSU_CLIENT_ID'),
			'client_secret' => env('UMAMUSU_CLIENT_SECRET'),
			//'scope'         => [],				// No scope - oauth1 doesn't need scope
		],
		'MyGoogle' => [
			'client_id'     => env('GOOGLE_CLIENT_ID'),
			'client_secret' => env('GOOGLE_CLIENT_SECRET'),
			'scope'         => ['https://www.googleapis.com/auth/datastore'],
		],

	]

];
