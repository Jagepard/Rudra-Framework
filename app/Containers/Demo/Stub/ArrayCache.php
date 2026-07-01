<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace App\Containers\Demo\Stub;

use App\Containers\Demo\Contract\CacheInterface;

class ArrayCache implements CacheInterface
{
    private array $storage = [];

    #[\Override]
    public function get(string $key): mixed
    {
        [$value, $expire] = $this->storage[$key] ?? [null, 0];
        return ($expire > time()) ? unserialize($value) : null;
    }

    #[\Override]
    public function set(string $key, mixed $value, int $ttl = 3600): void
    {
        $this->storage[$key] = [serialize($value), time() + $ttl];
        echo "[STUB] Cached: $key\n";
    }
}
