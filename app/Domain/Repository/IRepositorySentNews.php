<?php

namespace App\Domain\Repository;

interface IRepositorySentNews
{
    public function deleteAllThatBefore(int $oldest_timestamp);
}