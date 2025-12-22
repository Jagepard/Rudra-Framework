<?php

namespace App\Containers\Demo\Service;

use App\Containers\Demo\Contract\SmsSenderInterface;

class TwilioSmsSender implements SmsSenderInterface
{
    public function send(string $phone, string $message): bool
    {
        // В реальности — HTTP-запрос к Twilio
        echo "[PROD] SMS to $phone: $message\n";
        return true;
    }
}
