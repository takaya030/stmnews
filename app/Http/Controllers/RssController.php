<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use App\Models\Google\Datastore;
use App\Models\Google\News\Item as NewsItem;
use App\Models\Twitter\Tweet;
use App\Models\Twitter\UmaTweet;
use \Carbon\Carbon;
use Google\Cloud\Datastore\DatastoreClient;

class RssController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    // rss to twitter (stmnews)
	public function getRss(Request $request)
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

			$max_tweets = 2;
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

			return response()->json([
				'tweets_cnt' => $tweets_cnt,
				'last_timestamp' => $last_timestamp,
			]);
		}
		else
		{
			return response()->json([
				'error' => $feed->error(),
			]);
		}
	}

    // rss to twitter (umamusu)
	public function getUmarss(Request $request)
	{
		$feed = new \SimplePie();
		$feed->set_feed_url( config('accounts.rss.uma_url') );
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

			$max_tweets = 2;
			$tweets_cnt = 0;
			$last_timestamp = 0;
			if( isset( $data[0] ) )
			{
				$dsc = new DatastoreClient([
					'keyFilePath' => storage_path( config('accounts.google.key_file') )
				]);
				$datastore = new Datastore( $dsc, config('accounts.google.uma_datastore_kind') );

				$url_list = $this->makeStoredUrlList( $datastore );

				$tweet = new UmaTweet();

				foreach( $data as $news )
				{
					if( !in_array( $news->getUrl(), $url_list, true ) )
					{
						$tweet->postNewsItem( $news );

						$datastore->insertNewsItem( $news );

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

			return response()->json([
				'tweets_cnt' => $tweets_cnt,
				'last_timestamp' => $last_timestamp,
			]);
		}
		else
		{
			return response()->json([
				'error' => $feed->error(),
			]);
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

    // test delete entities
	public function getDelent(Request $request)
	{
		$dsc = new DatastoreClient([
			'keyFilePath' => storage_path( config('accounts.google.key_file') )
		]);
		$datastore = new Datastore( $dsc, config('accounts.google.datastore_kind') );

		$oldest_timestamp = Carbon::now()->subHours(36)->timestamp;
		$entities = $datastore->getBeforeAll( $oldest_timestamp );

		$delents = [];
		foreach( $entities as $entity )
		{
			$delents[] = $entity->key();
		}

		if( !empty( $delents ) )
		{
			$result = $datastore->deleteBatch( $delents );
		}

		return response()->json([
			'result' => 0,
		]);
	}
}
