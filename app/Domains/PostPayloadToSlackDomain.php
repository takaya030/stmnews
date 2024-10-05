<?php
declare(strict_types=1);

namespace App\Domains;

use App\Models\Google\Datastore;
use App\Models\Slack\Payload as Payload;
use App\Models\Slack\Post as SlackPost;
use Google\Cloud\Datastore\DatastoreClient;
use Throwable;

class PostPayloadToSlackDomain {

    /**
     * @param string $news_url
     * @param string $title
     * @param int $timestamp
     * @param string $slack_url
     * @param string $datastore_kind
     * @return array
     */
    public function __invoke(string $news_url, string $title, int $timestamp, string $slack_url, string $datastore_kind): array
    {
        try {
            $dsc = new DatastoreClient();
            $datastore = new Datastore( $dsc, $datastore_kind );
            $slackpost = new SlackPost($slack_url);

            $payload = new Payload($title, $news_url, $timestamp);
            $slackpost->postPayload($payload);
            $datastore->insertPayload($payload);

			app('log')->info('successed in posting: ' . json_encode($payload));

            return $payload->toArray();
        }
        catch(Throwable $e)
        {
			app('log')->error($e->getMessage());
            throw $e;
        }
    }
}