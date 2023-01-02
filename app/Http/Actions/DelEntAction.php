<?php
declare(strict_types=1);

namespace App\Http\Actions;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Controllers\Controller;

use App\Domains\DelEntDomain as Domain;

use App\Http\Responders\DelEntJsonResponder as Responder;

class DelEntAction extends Controller
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
        return $this->Responder->response(
            $this->Domain->get()
        );
    }
}
