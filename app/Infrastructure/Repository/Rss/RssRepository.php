<?php

namespace App\Infrastructure\Repository\Rss;

abstract class RssRepository
{
    protected $rss;

    public function __construct(\SimplePie\SimplePie $sp)
    {
        $this->rss = $sp;
    }
}
