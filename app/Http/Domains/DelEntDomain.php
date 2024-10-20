<?php
declare(strict_types=1);

namespace App\Http\Domains;

use App\Models\Google\Datastore;
use \Carbon\Carbon;
use Google\Cloud\Datastore\DatastoreClient;

class DelEntDomain
{
    /**
     * @return array
     */
    public function get(string $datastore_kind)
    {
		$dsc = new DatastoreClient();
		$datastore = new Datastore( $dsc, $datastore_kind );

		$oldest_timestamp = Carbon::now()->subHours(36)->timestamp;
		$entities = $datastore->getBeforeAll( $oldest_timestamp );

		$delents = [];
		foreach( $entities as $entity )
		{
			$delents[] = $entity->key();
		}

		if( !empty( $delents ) )
		{
			$result = $datastore->deleteBatch( $delents );
			app('log')->info('delete ent ids: ' . implode(",",$delents));
		}

		return [
			'del_ents_cnt' => count($delents),
		];
    }
}
