<?php

namespace App\Ship\Utils\Database\Driver;

use Rudra\Container\Facades\Rudra;

class MySQL
{
    public function __construct(protected $table){}

    public function isTable()
    {
        $query = Rudra::get("DSN")->query("
            SHOW TABLES LIKE '{$this->table}';
        ");

        return $query->fetchColumn();
    }
}
