<?php

namespace App\Infrastructure\Repository\Rss;

use App\Domain\Entity\News;
use App\Domain\Repository\IRepositoryNews;

class RssRepositoryNews extends RssRepository implements IRepositoryNews
{
    public function fetch(string $url): array
    {
		$this->rss->set_feed_url($url);
		$this->rss->enable_cache(false); //キャッシュ機能はオフで使う
		$success = $this->rss->init();
		$this->rss->handle_content_type();

		$data = [];
		if ($success)
		{
			foreach ($this->rss->get_items() as $item) {
                $data[] = new News( $item );
			}
        }
        else
        {
            app('log')->error($this->rss->error());
        }

        return $data;
    }
}
