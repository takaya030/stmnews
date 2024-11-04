<?php

namespace App\Infrastructure\Repository\Datastore;

use App\Domain\Repository\IRepositorySentNews;

class DatastoreRepositorySentNews extends DatastoreRepository implements IRepositorySentNews
{
    public function setKind(string $kd): void
    {
        $this->datastore->setKind($kd);
    }

    public function deleteAllThatBefore(int $oldest_timestamp): array
    {
		$entities = $this->datastore->getBeforeAll( $oldest_timestamp );

		$delents = [];
		foreach( $entities as $entity )
		{
			$delents[] = $entity->key();
		}

		if( !empty( $delents ) )
		{
			$result = $this->datastore->deleteBatch( $delents );
			app('log')->info('delete ent ids: ' . implode(",",$delents));
		}

		return [
			'del_ents_cnt' => count($delents),
		];
    }
}