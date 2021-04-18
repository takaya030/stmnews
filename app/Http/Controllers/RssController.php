<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use \Carbon\Carbon;

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
			dd( $data );
		}
		else
		{
			dd( $feed->error() );
		}
	}
}
