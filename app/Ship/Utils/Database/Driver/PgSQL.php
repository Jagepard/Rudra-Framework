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

class PgSQL
{
    public function __construct(protected $table){}

    public function isTable()
    {
        $query = Rudra::get('connection')->query("
        SELECT EXISTS (
            SELECT FROM information_schema.tables 
            WHERE  table_schema = 'public'
            AND    table_name   = '{$this->table}'
            );
        ");

        return $query->fetchColumn();
    }
}
