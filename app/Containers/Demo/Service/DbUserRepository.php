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

use App\Containers\Demo\Contract\UserRepositoryInterface;

class DbUserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?array
    {
        // Просто имитация БД
        return match ($id) {
            1 => ['id' => 1, 'name' => 'Real Alice'],
            2 => ['id' => 2, 'name' => 'Real Bob'],
            default => null,
        };
    }

    public function save(array $user): bool
    {
        // В реальности — INSERT/UPDATE
        return true;
    }
}
