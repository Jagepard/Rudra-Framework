<?php

namespace App\Containers\Demo\Service;

use App\Containers\Demo\Contract\UserRepositoryInterface;
use App\Containers\Demo\Contract\SmsSenderInterface;

class SomeDemoService
{
    public function __construct(
        private UserRepositoryInterface $users,
        private SmsSenderInterface $sms
    ) {}

    public function greet(int $id): void
    {
        if ($user = $this->users->findById($id)) {
            $this->sms->send('+79991234567', "Hello, {$user['name']}!");
        }
    }
}