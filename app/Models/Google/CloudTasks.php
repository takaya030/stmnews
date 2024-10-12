<?php

namespace App\Models\Google;

use Google\Cloud\Tasks\V2\Client\CloudTasksClient;

class CloudTask
{
	protected $client = null;
	protected $queueName = '';

	public function __construct(CloudTasksClient $ctc)
	{
		$this->client = $ctc;
	}

    public function setQueueName(string $projectId, string $locationId, string $queueId): void
    {
        $this->queueName = $this->client->queueName($projectId, $locationId, $queueId);
    }
}
