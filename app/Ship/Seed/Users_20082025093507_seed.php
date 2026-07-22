<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace App\Ship\Seed;

use Rudra\Auth\AuthFacade as Auth;

class Users_20082025093507_seed extends AbstractSeed
{
    #[\Override]
    public function create(): void
    {
        $table  = "users";
        $fields = [
            "name"     => "Admin",
            "email"    => "admin@admin.com",
            "password" => Auth::bcrypt('password'),
            "role"     => "admin",
            "activate" => md5(random_bytes(16)),
            "status"   => 1,
            "created_at" => date('Y-m-d H:i:s'),
            "updated_at" => date('Y-m-d H:i:s'),
        ];

        $this->execute($table, $fields);
    }
}
