<?php
declare(strict_types=1);

namespace App\Domains;

use App\Models\Google\Datastore;
use App\Models\Google\News\Item as NewsItem;
use App\Models\Slack\Post as SlackPost;
use \Carbon\Carbon;
use Google\Cloud\Datastore\DatastoreClient;

class GetRssToSlackDomain
{
    /**
     * @param int $limit
     * @return array
     */
    public function get(string $rss_url, string $datastore_kind, int $limit = 1)
    {
		$feed = new \SimplePie\SimplePie();
		$feed->set_feed_url( $rss_url );
		$feed->enable_cache(false); //キャッシュ機能はオフで使う
		$success = $feed->init();
		$feed->handle_content_type();

		if ($success)
		{
			$data = [];
			$oldest_timestamp = Carbon::now()->subHours(36)->timestamp;
			foreach ($feed->get_items() as $item) {
				$news = new NewsItem( $item );
				if( $news->getTimestamp() > $oldest_timestamp )
				{
					array_unshift( $data, $news );
				}
			}

			$max_posts = $limit;
			$posts_cnt = 0;
			$last_timestamp = 0;
			if( isset( $data[0] ) )
			{
				$dsc = new DatastoreClient([
					'keyFilePath' => storage_path( config('accounts.google.key_file') )
				]);
				$datastore = new Datastore( $dsc, $datastore_kind );

				$url_list = $this->makeStoredUrlList( $datastore );

				$slackpost = new SlackPost();

				foreach( $data as $news )
				{
					if( !in_array( $news->getUrl(), $url_list, true ) )
					{
						$slackpost->postNewsItem( $news );

						$datastore->insertNewsItem( $news );

						app('log')->info('post url: ' . $news->getUrl());

						$last_timestamp = $news->getTimestamp();
						$posts_cnt++;
						sleep(1);
					}

					if( $posts_cnt >= $max_posts )
					{
						break;
					}
				}

			}

			app('log')->info('posts cnt: ' . $posts_cnt . ', last_timestamp: ' . $last_timestamp);

			return [
				'posts_cnt' => $posts_cnt,
				'last_timestamp' => $last_timestamp,
			];
		}
		else
		{
			return [
				'error' => $feed->error(),
			];
		}
    }

	private function makeStoredUrlList( Datastore $ds )
	{
		$result = [];
		$entities = $ds->getAll();

		foreach( $entities as $entity )
		{
			$result[] = $entity['url'];
		}

		return $result;
	}
}
