<?php

namespace App\Models\Slack;

//use App\Models\Google\News\Item as NewsItem;
//use Throwable;

class Payload
{
	protected	$title;
	protected	$url;
    protected   $timestamp;

	/**
	 * @param string $title
     * @param string $url
	 */
	public function __construct(string $title, string $url, int $timestamp)
	{
		$this->title	= $title;   // news title
		$this->url		= $url;     // news url
        $this->timestamp    = $timestamp;
	}

    public function toArray(): array
    {
		return get_object_vars($this);
    }

	public function toJson(): string|false
	{
		return json_encode($this->toArray());
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function getUrl(): string
	{
		return $this->url;
	}

	public function getTimestamp(): int
	{
		return $this->timestamp;
	}
}