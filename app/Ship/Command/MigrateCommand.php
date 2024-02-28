<?php

namespace App\Ship\Command;

use Rudra\Cli\ConsoleFacade as Cli;
use Rudra\Container\Facades\Rudra;

class MigrateCommand
{
    private \PDO $dsn;
    private string $table;

    public function __construct()
    {
        $this->dsn   = Rudra::get("DSN");
        $this->table = "rudra_migrations";
    }

    public function actionIndex()
    {
        Cli::printer("Enter container (empty for Ship): ", "magneta");
        $container = ucfirst(str_replace(PHP_EOL, "", Cli::reader()));

        if (!empty($container)) {
            $fileList  = array_slice(scandir(str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Containers/" . $container . "/Migration/")), 2);
            $namespace = "App\\Containers\\$container\\Migration\\";
        } else {
            $fileList  = array_slice(scandir(str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Ship/Migration/")), 2);
            $namespace = "App\\Ship\\Migration\\";
        }

        if (!$this->isTable()) {
            $this->up();
        }

        foreach ($fileList as $filename) {
            $migrationName = $namespace . strstr($filename, '.', true);

            if ($this->checkMigration($migrationName)) {
                Cli::printer("The $migrationName is already migrated" . PHP_EOL, "light_yellow");
            } else {
                (new $migrationName)->up();
                Cli::printer("The $migrationName has migrate" . PHP_EOL, "light_green");
                $this->writeMigration($migrationName);
            }
        }
    }

    private function up()
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

    private function isTable()
    {
        $query = $this->dsn->query("
            SHOW TABLES LIKE '{$this->table}';
        ");

        return $query->fetchColumn();
    }

    private function writeMigration(string $name)
    {
        $query = $this->dsn->prepare("
            INSERT INTO {$this->table} (`name`)
            VALUES (:name)"
        );

        $query->execute([':name' => $name]);
    }

    private function checkMigration(string $name)
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
