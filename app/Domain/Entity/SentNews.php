<?php
declare(strict_types=1);

namespace App\Domain\Entity;

class SentNews
{
    /**
     * @var string $user_id
     */
	private	$user_id;

    /**
     * @var int $timestamp
     */
	private	$timestamp;

    /**
     * @var string $url
     */
	private	$url;

	/**
	 * @param string $user_id
	 * @param int $timestamp
	 * @param string $url
	 */
	public function __construct(string $user_id, int $timestamp, string $url)
	{
		$this->user_id	= $user_id;
		$this->timestamp	= $timestamp;
		$this->url		= $url;
	}

	public function getUserId(): string
	{
		return $this->user_id;
	}

	public function getTimestamp(): int
	{
		return $this->timestamp;
	}

	public function getUrl(): string
	{
		return $this->url;
	}
}

