<?php

namespace App\Containers\Demo\Contract;

interface SmsSenderInterface
{
    public function send(string $phone, string $message): bool;
}
