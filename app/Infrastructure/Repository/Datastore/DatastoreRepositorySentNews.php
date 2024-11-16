<?php

namespace App\Infrastructure\Repository\Datastore;

use App\Domain\Repository\IRepositorySentNews;
use App\Domain\Entity\News;
use App\Domain\Entity\SentNews;
use App\Domain\Entity\SNSPayload;

class DatastoreRepositorySentNews extends DatastoreRepository implements IRepositorySentNews
{
    public function setKind(string $kd): void
    {
        $this->datastore->setKind($kd);
    }

	public function getAll(): array
	{
		$entities = $this->datastore->getAll();

		$data = [];
		foreach( $entities as $entity )
		{
			$data[] = new SentNews($entity['user_id'], (int)$entity['timestamp'], $entity['url']);
		}

		return $data;
	}

    public function insertNews(News $news): string
	{
		$user_id = config('accounts.twitter.user_id');

		return $this->datastore->insert([
			'user_id'	=> $user_id,
			'timestamp' => $news->getTimestamp(),
			'url' => $news->getUrl(),
		]);
	}

    public function insertSNSPayload(SNSPayload $payload): string
	{
		$user_id = config('accounts.twitter.user_id');

		return $this->datastore->insert([
			'user_id'	=> $user_id,
			'timestamp' => $payload->getTimestamp(),
			'url' => $payload->getUrl(),
		]);
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