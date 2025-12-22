<?php

namespace App\Containers\Demo\Stub;

use App\Containers\Demo\Contract\CacheInterface;

class ArrayCache implements CacheInterface
{
    private array $storage = [];

    public function get(string $key): mixed
    {
        [$value, $expire] = $this->storage[$key] ?? [null, 0];
        return ($expire > time()) ? unserialize($value) : null;
    }

    public function set(string $key, mixed $value, int $ttl = 3600): void
    {
        $this->storage[$key] = [serialize($value), time() + $ttl];
        echo "[STUB] Cached: $key\n";
    }
}
