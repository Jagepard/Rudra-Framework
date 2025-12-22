<?php

namespace App\Containers\Demo\Stub;

use App\Containers\Demo\Contract\SmsSenderInterface;

class FakeSmsSender implements SmsSenderInterface
{
    public array $sent = [];

    public function send(string $phone, string $message): bool
    {
        $this->sent[] = compact('phone', 'message');
        echo "[STUB] Captured SMS: $phone → $message\n";
        return true;
    }
}
