<?php
declare(strict_types=1);

namespace App\Domains;

use App\Models\Google\Datastore;
use \Carbon\Carbon;
use Google\Cloud\Datastore\DatastoreClient;

class DelEntDomain
{
    /**
     * @return array
     */
    public function get()
    {
		$dsc = new DatastoreClient([
			'keyFilePath' => storage_path( config('accounts.google.key_file') )
		]);
		$datastore = new Datastore( $dsc, config('accounts.google.datastore_kind') );

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
