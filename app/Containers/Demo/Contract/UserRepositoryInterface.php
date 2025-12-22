<?php

namespace App\Containers\Demo\Contract;

interface UserRepositoryInterface
{
    public function findById(int $id): ?array;
    public function save(array $user): bool;
}
