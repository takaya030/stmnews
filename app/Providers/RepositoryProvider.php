<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Repository\IRepositorySentNews;
use App\Domain\Repository\IRepositoryNews;

use App\Infrastructure\Repository\Datastore\DatastoreRepositorySentNews;
use App\Infrastructure\Repository\Rss\RssRepositoryNews;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(IRepositorySentNews::class, DatastoreRepositorySentNews::class);
        $this->app->bind(IRepositoryNews::class, RssRepositoryNews::class);
    }
}
