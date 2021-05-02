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
			foreach ($feed->get_items() as $item) {
				array_unshift( $data, new NewsItem( $item ) );
			}

			if( isset( $data[0] ) )
			{
				$dsc = new DatastoreClient([
					'keyFilePath' => storage_path( config('accounts.google.key_file') )
				]);

				$datastore = new Datastore( $dsc, config('accounts.google.datastore_kind') );
				$datastore->insert([
					'user_id'	=> config('accounts.twitter.user_id'),
					'timestamp' => $data[0]->getTimestamp(),
					'url' => $data[0]->getUrl(),
				]);
			}

			dd( $data );
		}
		else
		{
			return response()->json([
				'error' => $feed->error(),
			]);
		}
	}

    // test getting datastore
	public function getData(Request $request)
	{
		$dsc = new DatastoreClient([
			'keyFilePath' => storage_path( config('accounts.google.key_file') )
		]);

		$datastore = new Datastore( $dsc, config('accounts.google.datastore_kind') );
		$entitys = $datastore->getAll();
		dd($entitys);
	}
}
