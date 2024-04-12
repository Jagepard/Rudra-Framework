<?php

namespace App\Ship\Utils\Database\Driver;

use Rudra\Container\Facades\Rudra;

class SQLite
{
    public function __construct(protected $table){}

    public function up(): void
    {
        Rudra::get("DSN")->prepare("
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
        $query = Rudra::get("DSN")->query("
            SELECT name FROM sqlite_master WHERE type='table' AND name='{$this->table}';
        ");

        return $query->fetchColumn();
    }

    public function writeLog(string $name): void
    {
        $query = Rudra::get("DSN")->prepare("
            INSERT INTO {$this->table} (name)
            VALUES (:name)"
        );

        $query->execute([':name' => $name]);
    }
}
