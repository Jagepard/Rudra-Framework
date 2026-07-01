<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace App\Containers\Demo\Service;

use App\Containers\Demo\Contract\CacheInterface;

class RedisCache implements CacheInterface
{
    #[\Override]
    public function get(string $key): mixed
    {
        echo "[PROD] Getting from Redis: $key\n";
        return null; // Simplified
    }

    #[\Override]
    public function set(string $key, mixed $value, int $ttl = 3600): void
    {
        echo "[PROD] Storing in Redis: $key = " . json_encode($value) . "\n";
    }
}
