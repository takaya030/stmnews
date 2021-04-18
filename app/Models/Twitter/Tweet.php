<?php

namespace App\Models\Twitter;

class Tweet
{
	protected	$id	= 0;
	protected	$created_at		= "";
	protected	$full_text		= "";
	protected	$lang			= "ja";

	protected	$name			= "";
	protected	$screen_name	= "";

	public function __construct( array $tw = [] )
	{
		$this->id			= $tw['id'] ?: 0;
		$this->created_at	= $tw['created_at'] ?: "";
		$this->full_text	= $tw['full_text'] ?: "";
		$this->lang			= $tw['lang'] ?: "ja";
		if( is_array($tw['user']) )
			$this->name			=  $tw['user']['name'] ?: "";
			$this->screen_name	=  $tw['user']['screen_name'] ?: "";
	}

	public function getStatusUrl(): string
	{
		return ( $this->id != 0 && $this->screen_name !== "")? "https://twitter.com/{$this->screen_name}/status/{$this->id}" : "";
	}

	public function getCreatedAt(): string
	{
		return $this->created_at;
	}

	public function getFullText(): string
	{
		return ( $this->full_text !== "")? str_replace( "\n", "<br>", $this->full_text ) : "";
	}

	public function getLang(): string
	{
		return $this->lang;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getScreenName(): string
	{
		return $this->screen_name;
	}
}
