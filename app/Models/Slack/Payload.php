<?php

namespace App\Models\Slack;

//use App\Models\Google\News\Item as NewsItem;
//use Throwable;

class Payload
{
	protected	$title;
	protected	$url;

	/**
	 * @param string $title
     * @param string $url
	 */
	public function __construct(string $title, string $url)
	{
		$this->title	= $title;   // news title
		$this->url		= $url;     // news url
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function getUrl(): string
	{
		return $this->url;
	}
}