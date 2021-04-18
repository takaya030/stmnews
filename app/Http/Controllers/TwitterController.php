<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;

use App\Models\Twitter\Timeline;
use App\Models\Twitter\Tweet;

class TwitterController extends Controller
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

    // test gettine timeline
	public function getTimeline(Request $request)
	{
		$model = new Timeline();
		$result = $model->getTimeline();
		dd($result);

		$tweets = [];
		if( is_array($result) )
		{
			foreach( $result as $tw )
			{
				$tweets[] = new Tweet( $tw );
			}
		}

		return view("timeline", ['tweets' => $tweets]);
	}
}
