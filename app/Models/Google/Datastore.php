<?php

namespace App\Models\Google;

use Google\Cloud\Datastore\Key;
use Google\Cloud\Datastore\DatastoreClient;
use App\Models\Google\News\Item as NewsItem;
use App\Models\Slack\Payload as Payload;

class Datastore
{
	protected $dsclient = null;
	protected $kind = '';
	protected $entities = [];
	protected $is_cached_entities = false;

	public function __construct(DatastoreClient $dsc)
	{
		$this->dsclient = $dsc;
		//$this->kind = $knd;
		$this->entities = [];
		$this->is_cached_entities = false;
	}

	public function setKind(string $datastore_kind): void
	{
		$this->kind = $datastore_kind;
	}

	public function getAll(): array
	{
		if( !$this->is_cached_entities )
		{
			$query = $this->dsclient->gqlQuery('SELECT * FROM ' . $this->kind );
			$res = $this->dsclient->runQuery($query);

			$this->entities = [];
			foreach( $res as $ent ) {
				$this->entities[] = $ent;
			}
			$this->is_cached_entities = true;
		}

		return $this->entities;
	}

	public function getBeforeAll( int $timestamp ): array
	{
		$query = $this->dsclient->gqlQuery('SELECT * FROM ' . $this->kind . ' WHERE timestamp < @tm', [
			'bindings' => [
				'tm' =>	$timestamp
			]
		]);
		$res = $this->dsclient->runQuery($query);

		$result = [];
		foreach( $res as $ent ) {
			$result[] = $ent;
		}

		return $result;
	}

	public function insertNewsitem( NewsItem $news, $user_id = null ): string
	{
		if( is_null($user_id) )
		{
			$user_id = config('accounts.twitter.user_id');
		}

		return $this->insert([
			'user_id'	=> $user_id,
			'timestamp' => $news->getTimestamp(),
			'url' => $news->getUrl(),
		]);
	}

	public function insertPayload( Payload $payload, $user_id = null ): string
	{
		if( is_null($user_id) )
		{
			$user_id = config('accounts.twitter.user_id');
		}

		return $this->insert([
			'user_id'	=> $user_id,
			'timestamp' => $payload->getTimestamp(),
			'url' => $payload->getUrl(),
		]);
	}

	public function insert( array $properties ): string
	{
		$key = $this->dsclient->key( $this->kind, null, [ 'identifierType' => Key::TYPE_ID ] );
		$entity = $this->dsclient->entity( $key, $properties );

		return $this->dsclient->insert($entity);
	}

	public function deleteBatch( array $keys )
	{
		return $this->dsclient->deleteBatch( $keys );
	}
}
