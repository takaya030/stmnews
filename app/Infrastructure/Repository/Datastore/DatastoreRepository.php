<?php

namespace App\Infrastructure\Repository\Datastore;

use \App\Models\Google\Datastore;

abstract class DatastoreRepository
{
    protected $datastore;

    public function __construct(Datastore $ds)
    {
        $this->datastore = $ds;
    }
}