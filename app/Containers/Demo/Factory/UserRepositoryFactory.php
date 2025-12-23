<?php

namespace App\Containers\Demo\Factory;

use App\Containers\Demo\Contract\UserRepositoryInterface;
use App\Containers\Demo\Service\DbUserRepository;
use Rudra\Container\Interfaces\FactoryInterface;

class UserRepositoryFactory implements FactoryInterface
{
    public function create(): UserRepositoryInterface
    {
        return new DbUserRepository();
    }
}
