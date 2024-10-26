<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Repository\IRepositorySentNews;

use App\Infrastructure\Repository\Datastore\DatastoreRepositorySentNews;

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
    }
}
