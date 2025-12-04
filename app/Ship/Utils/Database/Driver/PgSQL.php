<?php

namespace App\Ship\Utils\Database\Driver;

use Rudra\Container\Facades\Rudra;

class PgSQL
{
    public function __construct(protected $table){}

    public function isTable()
    {
        $query = Rudra::get("DSN")->query("
        SELECT EXISTS (
            SELECT FROM information_schema.tables 
            WHERE  table_schema = 'public'
            AND    table_name   = '{$this->table}'
            );
        ");

        return $query->fetchColumn();
    }
}
