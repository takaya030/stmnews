<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use App\Models\Google\Datastore;
use App\Models\Google\News\Item as NewsItem;
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

    // test getting rss
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

			if( isset( $data[0] ) )
			{
				$dsc = new DatastoreClient([
					'keyFilePath' => storage_path( config('accounts.google.key_file') )
				]);
				$datastore = new Datastore( $dsc, config('accounts.google.datastore_kind') );

				$url_list = $this->makeStoredUrlList( $datastore );

				/*
				foreach( $data as $news )
				{
					if( !in_array( $news->getUrl(), $url_list, true ) )
					{
						$datastore->insert([
							'user_id'	=> config('accounts.twitter.user_id'),
							'timestamp' => $news->getTimestamp(),
							'url' => $news->getUrl(),
						]);

						break;
					}
				}
				 */

			}

			dd( $datastore->getAll() );
			//dd( $data );
			//dd( $url_list );
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

		//dd([$delents, $result]);
	}
}
