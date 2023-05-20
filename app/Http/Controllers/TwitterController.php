<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;

use \League\OAuth1\Client\Server\Twitter;
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
