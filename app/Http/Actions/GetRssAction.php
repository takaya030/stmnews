<?php
declare(strict_types=1);

namespace App\Http\Actions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Domains\GetRssDomain as Domain;
use App\Http\Responders\GetRssJsonResponder as Responder;

class GetRssAction extends Controller
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

        return new Responder( $this->Domain->get($limit) );
    }
}
