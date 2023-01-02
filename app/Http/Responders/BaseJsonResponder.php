<?php
declare(strict_types=1);

namespace App\Http\Responders;

use Google\Cloud\Core\Exception\NotFoundException;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Responsable;

abstract class BaseJsonResponder implements Responsable
{
    /**
     * @param mixed $data
     * @return JsonResponse
     */
    public function response($data): JsonResponse
    {
        if (!empty($data)) {
            $data = [
                'status'    => Response::HTTP_OK,
                'data'      => $data,
            ];
        } else {
            $data = [
                'status'    => Response::HTTP_NOT_FOUND,
                'data'      => [],
            ];
        }

        return response()->json($data);
    }


    abstract protected function getData(): mixed;

    protected function getStatus(): int
    {
        return !empty($this->getData()) ?  Response::HTTP_OK : Response::HTTP_NOT_FOUND;
    }

    public function toResponse($request): JsonResponse
    {
        return new JsonResponse( $this->getData(), $this->getStatus() );
    }
}
