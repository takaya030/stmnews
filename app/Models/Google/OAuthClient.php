<?php

namespace App\Models\Google;

use OAuth\OAuth2\Token\StdOAuth2Token;

class OAuthClient
{
	public function __construct( bool $is_refresh_token = false )
	{
		// to refresh access token
		$googleService = $this->getOauthService( $is_refresh_token );
	}

	protected function getOauthService( bool $is_refresh_token = false )
	{
		$acc = config('accounts.google');

		$access_token = $acc["access_token"];
		$refresh_token = $acc["refresh_token"];
		$service = app('oauth')->consumer('MyGoogle');

		if( $is_refresh_token )
		{
			if( !is_null($access_token) && !is_null($refresh_token) )
			{
				$dummy_token = new StdOAuth2Token( $access_token, $refresh_token );
				$token = $service->refreshAccessToken($dummy_token);
			}
			else
			{
				dd('No Access Token or Refresh Token.');
			}
		}

		return $service;

	}
}
