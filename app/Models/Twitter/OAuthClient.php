<?php

namespace App\Models\Twitter;

use OAuth\OAuth1\Token\StdOAuth1Token;

class OAuthClient
{
	protected $servive;

	public function __construct()
	{
		$this->service = $this->getOauthService();
	}

	protected function getOauthService()
	{
		$acc = config('accounts.twitter');

        $token = new StdOAuth1Token();
        $token->setRequestToken( $acc['access_token'] );
        $token->setRequestTokenSecret( $acc['access_token_secret'] );
        $token->setAccessToken( $acc['access_token'] );
        $token->setAccessTokenSecret( $acc['access_token_secret'] );

		$service = app('oauth')->consumer('Twitter');
		$service->getStorage()->storeAccessToken('Twitter', $token);

		return $service;
	}
}
