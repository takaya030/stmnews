<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;

use \League\OAuth1\Client\Server\Twitter;
use \League\OAuth2\Client\Provider\GenericProvider;
use \League\OAuth2\Client\OptionProvider\HttpBasicAuthOptionProvider;
use App\Models\Twitter\Timeline;
use App\Models\Twitter\Tweet;

class TwitterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

	public function getLoginv2(Request $request)
	{
		// get data from request
		$code  = $request->get('code');
		$state  = $request->get('state');
		$oauth2state = $request->session()->get('oauth2state');
		$oauth2pkceCode = $request->session()->get('oauth2pkceCode');

		$provider = new GenericProvider([
			'clientId'                => env("TWITTER_V2_CLIENT_ID"),     // The client ID assigned to you by the provider
			'clientSecret'            => env("TWITTER_V2_CLIENT_SECRET"), // The client password assigned to you by the provider
			'redirectUri'             => 'http://localhost:8000/loginv2',
			'urlAuthorize'            => 'https://twitter.com/i/oauth2/authorize',
			'urlAccessToken'          => 'https://api.twitter.com/2/oauth2/token',
			'urlResourceOwnerDetails' => 'https://service.example.com/resource'
		],
		[
			'optionProvider'		=> new HttpBasicAuthOptionProvider(),
		]);
		
		// If we don't have an authorization code then get one
		if ( empty($code) ) {
		
			$options = [
				'response_type' => 'code',
				'client_id' => env("TWITTER_V2_CLIENT_ID"),
				'redirect_uri' => 'http://localhost:8000/loginv2',
				'scope' => 'tweet.read tweet.write users.read offline.access',
				'state' => 'state',
				'code_challenge' => 'E9Melhoa2OwvFrEMTJguCHaoeK1t8URWbuGJSstw-cM',
				'code_challenge_method' => 's256',
			];

			// Fetch the authorization URL from the provider; this returns the
			// urlAuthorize option and generates and applies any necessary parameters
			// (e.g. state).
			$authorizationUrl = $provider->getAuthorizationUrl($options);
		
			// Get the state generated for you and store it to the session.
			$request->session()->put('oauth2state', $provider->getState());
		
			// Optional, only required when PKCE is enabled.
			// Get the PKCE code generated for you and store it to the session.
			$request->session()->put('oauth2pkceCode', $provider->getPkceCode());
		
			// Redirect the user to the authorization URL.
			return redirect($authorizationUrl);
		
		// Check given state against previously stored one to mitigate CSRF attack
		} elseif (empty($state) || empty($oauth2state) || $state !== $oauth2state) {
		
			if ( ! empty($oauth2state) ) {
				$request->session()->forget('oauth2state');
			}
		
			dd(['msg' => 'Invalid state', 'state' => $state, 'oath2state' => $oauth2state]);
		
		} else {
		
			try {
			
				// Optional, only required when PKCE is enabled.
				// Restore the PKCE code stored in the session.
				$provider->setPkceCode($oauth2pkceCode);
		
				// Try to get an access token using the authorization code grant.
				$accessToken = $provider->getAccessToken('authorization_code', [
					'code' => $code,
					'grant_type' => 'authorization_code',
					'client_id' => env("TWITTER_V2_CLIENT_ID"), 
					'redirect_uri' => 'http://localhost:8000/loginv2',
					'code_verifier' => 'dBjftJeZ4CVP-mB92K27uhbUJU1p1r_wW1gFWFOEjXk',
				]);

				dd($accessToken);
		
				// We have an access token, which we may use in authenticated
				// requests against the service provider's API.
				/*
				echo 'Access Token: ' . $accessToken->getToken() . "<br>";
				echo 'Refresh Token: ' . $accessToken->getRefreshToken() . "<br>";
				echo 'Expired in: ' . $accessToken->getExpires() . "<br>";
				echo 'Already expired? ' . ($accessToken->hasExpired() ? 'expired' : 'not expired') . "<br>";
				*/
		
				// Using the access token, we may look up details about the
				// resource owner.
				/*
				$resourceOwner = $provider->getResourceOwner($accessToken);
		
				var_export($resourceOwner->toArray());
				*/
		
				// The provider provides a way to get an authenticated API request for
				// the service, using the access token; it returns an object conforming
				// to Psr\Http\Message\RequestInterface.
				/*
				$request = $provider->getAuthenticatedRequest(
					'GET',
					'https://service.example.com/resource',
					$accessToken
				);
				*/
		
			} catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
		
				// Failed to get the access token or user details.
				dd($e);
		
			}
		
		}
	}

	public function getLogin(Request $request)
	{
		// get data from request
		$token  = $request->get('oauth_token');
		$verify = $request->get('oauth_verifier');
		
		// get HatenaBookmark service
		$service = new Twitter(array(
			'identifier' => env("TWITTER_CLIENT_ID"),
			'secret' => env("TWITTER_CLIENT_SECRET"),
			'callback_uri' => "http://localhost/",
		));
		
		// check if code is valid
		
		// if code is provided get user data and sign in
		if ( ! is_null($token) && ! is_null($verify))
		{
			// Retrieve the temporary credentials we saved before
			$temporaryCredentials = $request->session()->get('temporary_credentials');

			// We will now retrieve token credentials from the server
			$tokenCredentials = $service->getTokenCredentials($temporaryCredentials, $token, $verify);	

			//Var_dump
			//display whole array.
			dd($tokenCredentials);
		}
		// if not ask for permission first
		else
		{
			// Retrieve temporary credentials
			$temporaryCredentials = $service->getTemporaryCredentials();

			// Store credentials in the session, we'll need them later
			$request->session()->put('temporary_credentials', $temporaryCredentials);

			// Second part of OAuth 1.0 authentication is to redirect the
			// resource owner to the login screen on the server.
			$url = $service->getAuthorizationUrl($temporaryCredentials);
			return redirect((string)$url);
		}
	}

    // test gettine timeline
	public function getTimeline(Request $request)
	{
		$model = new Timeline();
		$result = $model->getTimeline();
		dd($result);

		$tweets = [];
		if( is_array($result) )
		{
			foreach( $result as $tw )
			{
				$tweets[] = new Tweet( $tw );
			}
		}

		return view("timeline", ['tweets' => $tweets]);
	}
}
