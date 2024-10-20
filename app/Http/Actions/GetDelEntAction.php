<?php
declare(strict_types=1);

namespace App\Http\Actions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Domains\DelEntDomain as Domain;
use App\Http\Responders\DelEntJsonResponder as Responder;

class GetDelEntAction extends Controller
{
    protected $Domain;

    public function __construct( Domain $Domain )
    {
        $this->Domain     = $Domain;
    }

    /**
     * @param Request $request
     * @return DelEntJsonResponder
     */
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'datastore_kind' => 'required|string',
        ]);

        $datastore_kind = $request->input('datastore_kind');
        return new Responder( $this->Domain->get($datastore_kind) );
    }
}
