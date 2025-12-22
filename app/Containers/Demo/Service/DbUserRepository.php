<?php

namespace App\Containers\Demo\Service;

use App\Containers\Demo\Contract\UserRepositoryInterface;

class DbUserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?array
    {
        // Просто имитация БД
        return match ($id) {
            1 => ['id' => 1, 'name' => 'Real Alice'],
            2 => ['id' => 2, 'name' => 'Real Bob'],
            default => null,
        };
    }

    public function save(array $user): bool
    {
        // В реальности — INSERT/UPDATE
        return true;
    }
}
