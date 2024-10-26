<?php

namespace App\Domain\Repository;

interface IRepositorySentNews
{
    public function setKind(string $kd);
    public function deleteAllThatBefore(int $oldest_timestamp);
}