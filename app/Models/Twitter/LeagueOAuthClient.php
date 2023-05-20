<?php

namespace App\Models\Twitter;

use Throwable;

class LeagueOAuthClient
{
	protected $service;
	protected $tokenCredentials;
	protected $client;
	protected $base_url = 'https://api.twitter.com/1.1/';

	public function __construct()
	{
		$this->service = $this->getOauthService();
		$this->tokenCredentials = $this->getTokenCredentials();
		$this->client = new \GuzzleHttp\Client();
	}

	protected function getOauthService()
	{
		// Create server
		$service = new \League\OAuth1\Client\Server\Twitter(array(
			'identifier' => env("TWITTER_CLIENT_ID"),
			'secret' => env("TWITTER_CLIENT_SECRET"),
			'callback_uri' => "http://localhost/",
		));

		return $service;
	}

	protected function getTokenCredentials()
	{
		$tokenCredentials = new \League\OAuth1\Client\Credentials\TokenCredentials();
		$tokenCredentials->setIdentifier(env("TWITTER_ACCESS_TOKEN"));
		$tokenCredentials->setSecret(env("TWITTER_ACCESS_TOKEN_SECRET"));

		return $tokenCredentials;
	}

	public function request(string $path, string $method, array $body = [], array $extraOption = [])
	{
		$url = $this->base_url . $path;

		$options = [];
		if($method == 'POST' && !empty($body) && is_array($body))
		{
			$options["form_params"] = $body;
		}
		if($method == 'GET' && !empty($body) && is_array($body))
		{
			$options["query"] = $body;
		}
		$options["headers"] = $this->service->getHeaders($this->tokenCredentials,$method,$url,$body);
		if(!empty($extraOption) && is_array($extraOption))
		{
			$options = array_merge($options,$extraOption);
		}

		try {
			$response = $this->client->request($method,$url,$options);
			return $response->getBody()->getContents();
		}
		catch(Throwable $e)
		{
			throw $e;
		}
	}

	/*
	public function getUserinfo()
	{
		$result = json_decode($this->request('user/info','GET'), true);

		return $result;
	}
	*/
}
