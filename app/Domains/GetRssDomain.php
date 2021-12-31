<?php
declare(strict_types=1);

namespace App\Domains;

use App\Models\Google\Datastore;
use App\Models\Google\News\Item as NewsItem;
use App\Models\Twitter\Tweet;
use \Carbon\Carbon;
use Google\Cloud\Datastore\DatastoreClient;

class GetRssDomain
{
    /**
     * @param int $limit
     * @return array
     */
    public function get(int $limit = 1)
    {
		$feed = new \SimplePie();
		$feed->set_feed_url( config('accounts.rss.url') );
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

			$max_tweets = $limit;
			$tweets_cnt = 0;
			$last_timestamp = 0;
			if( isset( $data[0] ) )
			{
				$dsc = new DatastoreClient([
					'keyFilePath' => storage_path( config('accounts.google.key_file') )
				]);
				$datastore = new Datastore( $dsc, config('accounts.google.datastore_kind') );

				$url_list = $this->makeStoredUrlList( $datastore );

				$tweet = new Tweet();

				foreach( $data as $news )
				{
					if( !in_array( $news->getUrl(), $url_list, true ) )
					{
						$tweet->postNewsItem( $news );

						$datastore->insertNewsItem( $news );

						app('log')->info('tweet url: ' . $news->getUrl());

						$last_timestamp = $news->getTimestamp();
						$tweets_cnt++;
						sleep(2);
					}

					if( $tweets_cnt >= $max_tweets )
					{
						break;
					}
				}

			}

			return [
				'tweets_cnt' => $tweets_cnt,
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
