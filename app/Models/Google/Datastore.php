<?php

namespace App\Models\Google;

use Google\Cloud\Datastore\DatastoreClient;
use \App\Models\Google\Datastore\Entity;

class Datastore
{
	protected $dsclient = null;
	protected $kind = '';
	protected $entities = [];
	protected $is_cached_entities = false;

	protected $list_labels = [];
	protected $base_url = '';

	public function __construct( DatastoreClient $dsc, string $knd )
	{
		$this->dsclient = $dsc;
		$this->kind = $kdn;
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

	public function upsert( string $kind, string $name, array $properties )
	{
		$googleService = $this->getOauthService();
		$projectId = config('accounts.google.project_id');
		$params_str= "";

		foreach( $properties as $key => $value )
		{
			if( empty($params_str) )
				$params_str = static::getPropertyString( $key, $value );
			else
				$params_str .= ',' . static::getPropertyString( $key, $value );
		}

		$body = <<< EOM
{
	"mode":"NON_TRANSACTIONAL",
	"mutations":[
		{
			"upsert": {
				"key":{ "partitionId":{ "projectId":"{$projectId}" }, "path":[ {"kind":"{$kind}","name":"{$name}"} ] },
				"properties": {
					{$params_str}
				}
			}
		}
	 ]
}
EOM;
		// Send a request with it
		$result = json_decode(
			$googleService->request(
				$this->base_url . ':commit',
				'POST',
				$body,
				[ 'Content-type' => 'application/json' ]
			),
			true
		);

		return $result;
	}

	static protected function getPropertyString( $key, $value )
	{
		$type_str = 'stringValue';

		if( is_numeric($value) )
			$type_str = 'integerValue';
		if( is_null($value) )
			$type_str = 'nullValue';

		return "\"{$key}\":{\"{$type_str}\":\"{$value}\"}";
	}
}
