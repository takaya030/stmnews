<?php
declare(strict_types=1);

namespace App\Http\Actions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Domains\PostPayloadToSlackDomain as Domain;
use App\Http\Responders\PostPlayloadToSlackJsonResponder as Responder;

class PostPayloadToSlackAction extends Controller
{
    protected $Domain;

    public function __construct(Domain $Domain)
    {
        $this->Domain     = $Domain;
    }

    /**
     * @param Request $request
     * @return GetRssJsonREsponder
     */
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'news_url' => 'required|url',
            'title' => 'required|string',
            'timestamp' => 'required|int',
            'slack_url' => 'required|url',
            'datastore_kind' => 'required|string',
        ]);

        $news_url = $request->input('rss_url');
        $title = $request->input('title');
        $timestamp = $request->input('timestamp');
        $slack_url = $request->input('slack_url');
        $datastore_kind = $request->input('datastore_kind');

        return new Responder( $this->Domain->__invoke($news_url, $title, $timestamp, $slack_url, $datastore_kind) );
    }
}
