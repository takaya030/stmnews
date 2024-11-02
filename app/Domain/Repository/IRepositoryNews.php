<?php

namespace App\Domain\Repository;

interface IRepositoryNews
{
    public function fetch(string $url): array;
}