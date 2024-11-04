<?php
declare(strict_types=1);

namespace App\Http\Domains;

use \Carbon\Carbon;

use App\Domain\Repository\IRepositoryNews as RepositoryNews;
use App\Domain\Repository\IRepositorySentNews as RepositorySentNews;
use App\Domain\Repository\IRepositorySNS as RepositorySNS;

class GetRssToSlackDomain
{
    protected $repoNews;
    protected $repoSentNews;
    protected $repoSNS;

    public function __construct(RepositoryNews $repoNews, RepositorySentNews $repoSentNews, RepositorySNS $repoSNS)
    {
        $this->repoNews = $repoNews;
        $this->repoSentNews = $repoSentNews;
        $this->repoSNS = $repoSNS;
    }

    /**
     * @param int $limit
     * @return array
     */
    public function get(string $rss_url, string $slack_url, string $datastore_kind, int $limit = 1)
    {
		$feed = $this->repoNews->fetch($rss_url);

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
				$this->repoSentNews->setKind($datastore_kind);

				$url_list = $this->makeStoredUrlList();

				$this->repoSNS->setUrl($slack_url);

				foreach( $data as $news )
				{
					if( !in_array( $news->getUrl(), $url_list, true ) )
					{
						$this->repoSNS->postNews($news);

						$this->repoSentNews->insertNews( $news );

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

	private function makeStoredUrlList()
	{
		$result = [];
		$entities = $this->repoSentNews->getAll();

		foreach( $entities as $sentnews )
		{
			$result[] = $sentnews->getUrl();
		}

		return $result;
	}
}
