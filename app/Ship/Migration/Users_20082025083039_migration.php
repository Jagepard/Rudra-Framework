<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace App\Ship\Migration;

use Rudra\Model\Schema;

class Users_20082025083039_migration
{
    public function up(): void
    {
        Schema::create('users', function ($table) {
            $table->integer('id', autoincrement: true)
                ->string('name')
                ->string('email', 'NOT NULL UNIQUE')
                ->string('password')
                ->string('role')
                ->string('activate')
                ->integer('status', 'DEFAULT 0')
                ->createdAt()
                ->updatedAt()
                ->primaryKey('id');
        })->execute();
    }
}
