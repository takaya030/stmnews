<?php
declare(strict_types=1);

namespace App\Http\Actions;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Controllers\Controller;

use App\Domains\GetRssDomain as Domain;

use App\Http\Responders\GetRssJsonResponder as Responder;

class GetRssAction extends Controller
{
    protected $Domain;
    protected $Responder;

    public function __construct(Domain $Domain, Responder $Responder)
    {
        $this->Domain     = $Domain;
        $this->Responder  = $Responder;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $this->validate($request, [
            'limit' => 'required|integer|min:1|max:5',
        ]);

		$limit = (int)$request->input('limit');

        return $this->Responder->response(
            $this->Domain->get($limit)
        );
    }
}
