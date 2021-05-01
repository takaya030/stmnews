<?php

namespace App\Models\Google;

use Google\Cloud\Datastore\Key;
use Google\Cloud\Datastore\DatastoreClient;

class Datastore
{
	protected $dsclient = null;
	protected $kind = '';
	protected $entities = [];
	protected $is_cached_entities = false;

	public function __construct( DatastoreClient $dsc, string $knd )
	{
		$this->dsclient = $dsc;
		$this->kind = $knd;
		$this->entities = [];
		$this->is_cached_entities = false;
	}

	public function getAll()
	{
		if( !$this->is_cached_entities )
		{
			$query = $this->dsclient->gqlQuery('SELECT * FROM ' . $this->kind );
			$res = $datastore->runQuery($query);

			$this->entities = [];
			foreach( $res as $ent ) {
				$this->entities[] = $ent;
			}
			$this->is_cached_entities = true;
		}

		return $this->entities;
	}

	public function insert( array $properties )
	{
		$key = $this->dsclient->key( $this->kind, null, [ 'identifierType' => Key::TYPE_ID ] );
		$entity = $this->dsclient->entity( $key, $properties );

		return $this->dsclient->insert($entity);
	}
}
