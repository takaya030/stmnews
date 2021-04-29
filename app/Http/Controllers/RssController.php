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

    // test gettine rss
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
				$data[] = new NewsItem( $item );
			}

			/*
			if( isset( $data[0] ) )
			{
				$datastore = new Datastore();
				$datastore->insert( env('DATASTORE_KIND'), [
					'user_id'	=> env('TWITTER_USER_ID'),
					'timestamp' => $data[0]['timestamp'],
					'url' => $data[0]['url'],
				] );
			}
			 */

			dd( $data );
		}
		else
		{
			dd( $feed->error() );
		}
	}

    // test gettine datastore
	public function getData(Request $request)
	{
		$datastore = new DatastoreClient([
			'keyFilePath' => storage_path( 'app/' . env('GOOGLE_KEY_FILE') )
		]);

		// If you know the ID of the entity, you can look it up
		$key = $datastore->key('StmNews', '5079418695319552');
		$entity = $datastore->lookup($key);
		dd($entity);
	}
}
