<?php
declare(strict_types=1);

namespace App\Http\Domains;

use \Carbon\Carbon;

use App\Domain\Repository\IRepositorySentNews as Repository;

class DelEntDomain
{
    protected $repository;

    public function __construct(Repository $repo)
    {
        $this->repository = $repo;
    }

    /**
     * @return array
     */
    public function get(string $datastore_kind)
    {
		    $this->repository->setKind($datastore_kind);

		    $oldest_timestamp = Carbon::now()->subHours(36)->timestamp;

		    return $this->repository->deleteAllThatBefore($oldest_timestamp);
    }
}
