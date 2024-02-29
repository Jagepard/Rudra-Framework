<?php

namespace App\Ship\Utils;

use Rudra\Container\Facades\Rudra;

class DatabaseLogger
{
    protected \PDO $dsn;

    public function __construct()
    {
        $this->dsn = Rudra::get("DSN");
    }

    protected function up()
    {
        $query = $this->dsn->prepare("
            CREATE TABLE {$this->table} (
            `id` INT NOT NULL AUTO_INCREMENT ,
            `name` VARCHAR(255) NOT NULL , 
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
            `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci
        ");

        $query->execute();
    }

    protected function isTable()
    {
        $query = $this->dsn->query("
            SHOW TABLES LIKE '{$this->table}';
        ");

        return $query->fetchColumn();
    }

    protected function writeLog(string $name)
    {
        $query = $this->dsn->prepare("
            INSERT INTO {$this->table} (`name`)
            VALUES (:name)"
        );

        $query->execute([':name' => $name]);
    }

    protected function checkLog(string $name)
    {
        $stmt = $this->dsn->prepare("
            SELECT * FROM {$this->table}
            WHERE name = :name
        ");

        $stmt->execute([
            ':name' => $name,
        ]);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}