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

use App\Containers\Demo\Contract\UserRepositoryInterface;

class FakeUserRepository implements UserRepositoryInterface
{
    private array $users = [
        99 => ['id' => 99, 'name' => 'Stub Charlie'],
    ];

    public function findById(int $id): ?array
    {
        return $this->users[$id] ?? null;
    }

    public function save(array $user): bool
    {
        $this->users[$user['id']] = $user;
        return true;
    }
}
