<?php

namespace App\Domain\Repository;

interface IRepositorySentNews
{
    public function setKind(string $kd): void;
    public function getAll(): array;
    public function deleteAllThatBefore(int $oldest_timestamp): array;
}