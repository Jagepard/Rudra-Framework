<?php

namespace App\Containers\Demo\Stub;

use App\Containers\Demo\Contract\UserRepositoryInterface;

class FakeUserRepository implements UserRepositoryInterface
{
    private array $users = [
        99 => ['id' => 99, 'name' => 'Stub Charlie'],
    ];

    public function findById(int $id): ?array
    {
        return $this->users[$id] ?? null;
    }

    public function save(array $user): bool
    {
        $this->users[$user['id']] = $user;
        return true;
    }
}
