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

    public function writeLog(string $name): void
    {
        $query = Rudra::get("DSN")->prepare("
            INSERT INTO {$this->table} (name, created_at)
            VALUES (:name, :created_at)"
        );

        $query->execute([
            ':name' => $name,
            ':created_at' => date('d-M-Y H:i:s')
        ]);
    }
}
