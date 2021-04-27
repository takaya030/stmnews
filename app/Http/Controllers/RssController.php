<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use App\Models\Google\Datastore;
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
		$feed->set_feed_url( env('RSS_URL') );
		$feed->enable_cache(false); //キャッシュ機能はオフで使う
		$success = $feed->init();
		$feed->handle_content_type();

		if ($success)
		{
			$data = [];
			foreach ($feed->get_items() as $item) {
				$data[] = [
					//'site_title'	=> $item->get_feed()->get_title(),		//サイトタイトル
					'title'			=> $item->get_title(),					//記事タイトル
					'url'			=> $item->get_link(),					//記事URL
					'date'			=> $item->get_date('Y-m-d H:i:s T'),	//記事投稿時刻
					'timestamp'		=> Carbon::createFromFormat( 'Y-m-d H:i:s T', $item->get_date('Y-m-d H:i:s T') )->timestamp,	//記事投稿時刻
				];
			}

			if( isset( $data[0] ) )
			{
				$datastore = new Datastore();
				$datastore->insert( env('DATASTORE_KIND'), [
					'user_id'	=> env('TWITTER_USER_ID'),
					'timestamp' => $data[0]['timestamp'],
					'url' => $data[0]['url'],
				] );
			}

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
