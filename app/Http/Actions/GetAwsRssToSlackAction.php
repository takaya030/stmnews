<?php
declare(strict_types=1);

namespace App\Http\Actions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Domains\GetRssToSlackDomain as Domain;
use App\Http\Responders\GetRssToSlackJsonResponder as Responder;

class GetAwsRssToSlackAction extends Controller
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
            'limit' => 'required|integer|min:1|max:5',
        ]);

		$limit = (int)$request->input('limit');
        $rss_url = config('accounts.rss.aws_url');
        $slack_url = config('accounts.slack.blog_url');
        $datastore_kind = config('accounts.google.aws_datastore_kind');

        return new Responder( $this->Domain->get($rss_url, $slack_url, $datastore_kind, $limit) );
    }
}
