<?php
declare(strict_types=1);

namespace App\Http\Domains;

use App\Domain\Entity\SNSPayload;
use App\Domain\Repository\IRepositorySentNews as RepositorySentNews;
use App\Domain\Repository\IRepositorySNS as RepositorySNS;

use App\Models\Google\Datastore;
use App\Models\Slack\Payload as Payload;
use App\Models\Slack\Post as SlackPost;
use Google\Cloud\Datastore\DatastoreClient;
use Throwable;

class PostPayloadToSlackDomain
{
    protected $repoSentNews;
    protected $repoSNS;

    public function __construct(RepositorySentNews $repoSentNews, RepositorySNS $repoSNS)
    {
        $this->repoSentNews = $repoSentNews;
        $this->repoSNS = $repoSNS;
    }

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
        try
        {
            $this->repoSentNews->setKind($datastore_kind);
            $this->repoSNS->setUrl($slack_url);

            $payload = new SNSPayload($title, $news_url, $timestamp);
            $this->repoSNS->postSNSPayload($payload);
            $this->repoSentNews->insertSNSPayload($payload);

			app('log')->info('successed in posting: ' . json_encode($payload->toArray()));

            return $payload->toArray();
        }
        catch(Throwable $e)
        {
			app('log')->error($e->getMessage());
            throw $e;
        }
    }
}