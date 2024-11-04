<?php

namespace App\Infrastructure\Repository\Slack;

use Throwable;

abstract class SlackRepository
{
    protected $client;
	protected $url;

    public function __construct(\GuzzleHttp\Client $gcl)
    {
        $this->client = $gcl;
    }

	/**
	 * @param string $text
	 * @return string
	 */
	public function postText(string $text): string
	{
        $method = 'POST';
        $url = $this->url;
        $options = [
            'json' => ['text' => $text],
        ];

		try {
			$response = $this->client->request($method,$url,$options);
			return $response->getBody()->getContents();
		}
		catch(Throwable $e)
		{
			throw $e;
		}
	}
}
