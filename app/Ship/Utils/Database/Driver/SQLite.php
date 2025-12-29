<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */
namespace App\Ship\Utils\Database\Driver;

use Rudra\Container\Facades\Rudra;

class SQLite
{
    public function __construct(protected $table){}

    public function up(): void
    {
        Rudra::get('connection')->prepare("
            CREATE TABLE {$this->table} (
                id INTEGER PRIMARY KEY,
                name TEXT NOT NULL,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP
            );
        ")->execute();
    }

    public function isTable()
    {
        $query = Rudra::get('connection')->query("
            SELECT name FROM sqlite_master WHERE type='table' AND name='{$this->table}';
        ");

        return $query->fetchColumn();
    }
}
