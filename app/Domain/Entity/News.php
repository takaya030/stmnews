<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use \Carbon\Carbon;

class News
{
    /**
     * @var string $title
     */
	private	$title;

    /**
     * @var string $url
     */
	private	$url;

    /**
     * @var string $date
     */
	private	$date;

    /**
     * @var int $timestamp
     */
	private	$timestamp;

	/**
	 * @param \SimplePie\Item $item
	 */
	public function __construct( \SimplePie\Item $item )
	{
		$this->title	= $item->get_title();	// news title
		$this->url		= $item->get_link();	// news url
		$this->date		= $item->get_date('Y-m-d H:i:s T');	// posting date of news
		$this->timestamp	= Carbon::createFromFormat( 'Y-m-d H:i:s T', $item->get_date('Y-m-d H:i:s T') )->timestamp;	// posting timestamp of news
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function getUrl(): string
	{
		return $this->url;
	}

	public function getDate(): string
	{
		return $this->date;
	}

	public function getTimestamp(): int
	{
		return $this->timestamp;
	}
}
