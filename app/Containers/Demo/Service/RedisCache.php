<?php

namespace App\Containers\Demo\Service;

use App\Containers\Demo\Contract\CacheInterface;

class RedisCache implements CacheInterface
{
    public function get(string $key): mixed
    {
        echo "[PROD] Getting from Redis: $key\n";
        return null; // упрощённо
    }

    public function set(string $key, mixed $value, int $ttl = 3600): void
    {
        echo "[PROD] Storing in Redis: $key = " . json_encode($value) . "\n";
    }
}
