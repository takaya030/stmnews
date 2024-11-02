<?php
declare(strict_types=1);

namespace App\Http\Domains;

use App\Models\Google\Datastore;
use App\Models\Google\News\Item as NewsItem;
use App\Models\Slack\Post as SlackPost;
use \Carbon\Carbon;
use Google\Cloud\Datastore\DatastoreClient;

use App\Domain\Repository\IRepositoryNews as Repository;

class GetRssToSlackDomain
{
    protected $repository;

    public function __construct(Repository $repo)
    {
        $this->repository = $repo;
    }

    /**
     * @param int $limit
     * @return array
     */
    public function get(string $rss_url, string $slack_url, string $datastore_kind, int $limit = 1)
    {
		$feed = $this->repository->fetch($rss_url);

		if (!empty($feed))
		{
			$data = [];
			$oldest_timestamp = Carbon::now()->subHours(36)->timestamp;
			foreach ($feed as $news) {
				if( $news->getTimestamp() > $oldest_timestamp )
				{
					array_unshift( $data, $news );
				}
			}

			$max_posts = $limit;
			$posts_cnt = 0;
			$last_timestamp = 0;
			if( isset( $data[0] ) )
			{
				$dsc = new DatastoreClient();
				$datastore = new Datastore( $dsc );
				$datastore->setKind($datastore_kind);

				$url_list = $this->makeStoredUrlList( $datastore );

				$slackpost = new SlackPost($slack_url);

				foreach( $data as $news )
				{
					if( !in_array( $news->getUrl(), $url_list, true ) )
					{
						$slackpost->postText( $news->getTitle() . "\n" . $news->getUrl() );

						$datastore->insertNews( $news );

						app('log')->info('post url: ' . $news->getUrl());

						$last_timestamp = $news->getTimestamp();
						$posts_cnt++;
						sleep(1);
					}

					if( $posts_cnt >= $max_posts )
					{
						break;
					}
				}

			}

			app('log')->info('posts cnt: ' . $posts_cnt . ', last_timestamp: ' . $last_timestamp);

			return [
				'posts_cnt' => $posts_cnt,
				'last_timestamp' => $last_timestamp,
			];
		}
		else
		{
			return [
				'error' => "cannot fetch rss feed: " . $rss_url,
			];
		}
    }

	private function makeStoredUrlList( Datastore $ds )
	{
		$result = [];
		$entities = $ds->getAll();

		foreach( $entities as $entity )
		{
			$result[] = $entity['url'];
		}

		return $result;
	}
}
