<?php

namespace App\Containers\Demo\Factory;

use App\Containers\Demo\Contract\UserRepositoryInterface;
use App\Containers\Demo\Service\DbUserRepository;

class UserRepositoryFactory
{
    public function create(): UserRepositoryInterface
    {
        return new DbUserRepository();
    }
}
