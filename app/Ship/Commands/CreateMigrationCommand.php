<?php

namespace App\Ship\Commands;

use Rudra\Cli\ConsoleFacade as Cli;
use Rudra\Container\Facades\Rudra;

class CreateMigrationCommand
{
    /**
     * Creates a file with Migration data
     * -----------------------------
     * Создает файл с данными Migration
     */
    public function actionIndex()
    {
        Cli::printer("Enter table name: ", "magneta");
        $table = str_replace(PHP_EOL, "", Cli::reader());

        Cli::printer("Enter container (empty for Ship): ", "magneta");
        $container = ucfirst(str_replace(PHP_EOL, "", Cli::reader()));

        $date      = date("_dmYHis");
        $className = ucfirst($table) . $date;

        if (!empty($container)) {

            $namespace = 'App\Containers\\' . $container . '\Migrations';

            if (Rudra::get("DSN")->getAttribute(\PDO::ATTR_DRIVER_NAME) === "mysql") {
                $this->writeFile([str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Containers/" . $container . "/Migrations/"), "{$className}_migration.php"],
                    $this->createMysqlMigration($className, $table, $namespace)
                );
            } elseif (Rudra::get("DSN")->getAttribute(\PDO::ATTR_DRIVER_NAME) === "pgsql") {
                $this->writeFile([str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Containers/" . $container . "/Migrations/"), "{$className}_migration.php"],
                    $this->createPgsqlMigration($className, $table, $namespace)
                );
            } elseif (Rudra::get("DSN")->getAttribute(\PDO::ATTR_DRIVER_NAME) === "sqlite") {
                $this->writeFile([str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Containers/" . $container . "/Migrations/"), "{$className}_migration.php"],
                    $this->createSqliteMigration($className, $table, $namespace)
                );
            }

        } else {

            $namespace = "App\Ship\Migrations";

            if (Rudra::get("DSN")->getAttribute(\PDO::ATTR_DRIVER_NAME) === "mysql") {
                $this->writeFile([str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Ship/Migrations/"), "{$className}_migration.php"],
                    $this->createMysqlMigration($className, $table, $namespace)
                );
            } elseif (Rudra::get("DSN")->getAttribute(\PDO::ATTR_DRIVER_NAME) === "pgsql") {
                $this->writeFile([str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Ship/Migrations/"), "{$className}_migration.php"],
                    $this->createPgsqlMigration($className, $table, $namespace)
                );
            } elseif (Rudra::get("DSN")->getAttribute(\PDO::ATTR_DRIVER_NAME) === "sqlite") {
                $this->writeFile([str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Ship/Migrations/"), "{$className}_migration.php"],
                    $this->createSqliteMigration($className, $table, $namespace)
                );
            }
        }
    }

    /**
     * @param string $className
     * @param string $container
     * @param string $table
     * @return string
     *
     * Creates class data
     * ------------------
     * Создает данные класса
     */
    private function createMysqlMigration(string $className, string $table, string $namespace)
    {
        return <<<EOT
<?php

namespace $namespace;

use Rudra\Container\Facades\Rudra;

class {$className}_migration
{
    public function up()
    {
        \$table = "$table";

        \$query = Rudra::get("DSN")->prepare("
            CREATE TABLE {\$table} (
            `id` INT NOT NULL AUTO_INCREMENT ,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
            `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci
        ");

        \$query->execute();
    }
}
EOT;
    }

    /**
     * @param string $className
     * @param string $container
     * @param string $table
     * @return string
     *
     * Creates class data
     * ------------------
     * Создает данные класса
     */
    private function createPgsqlMigration(string $className, string $table, string $namespace)
    {
        return <<<EOT
<?php

namespace $namespace;

use Rudra\Container\Facades\Rudra;

class {$className}_migration
{
    public function up()
    {
        \$table = "$table";

        \$query = Rudra::get("DSN")->prepare("

            CREATE TABLE {\$table} (
                id serial PRIMARY KEY,
                created_at TIMESTAMP NOT NULL,
                updated_at TIMESTAMP NOT NULL
            );
        ");

        \$query->execute();
    }
}
EOT;
    }

        /**
     * @param string $className
     * @param string $container
     * @param string $table
     * @return string
     *
     * Creates class data
     * ------------------
     * Создает данные класса
     */
    private function createSqliteMigration(string $className, string $table, string $namespace)
    {
        return <<<EOT
<?php

namespace $namespace;

use Rudra\Container\Facades\Rudra;

class {$className}_migration
{
    public function up()
    {
        \$table = "$table";

        \$query = Rudra::get("DSN")->prepare("

            CREATE TABLE IF NOT EXISTS {\$table} (
                id INTEGER PRIMARY KEY,
                created_at TEXT NOT NULL,
                updated_at TEXT NOT NULL
            );
        ");

        \$query->execute();
    }
}
EOT;
    }

    /**
     * @param $path
     * @param $callable
     *
     * Writes data to a file
     * ---------------------
     * Записывает данные в файл
     */
    private function writeFile($path, $callable)
    {
        if (!is_dir($path[0])) {
            mkdir($path[0], 0755, true);
        }

        $fullPath = $path[0] . $path[1];

        if (!file_exists($fullPath)) {
            Cli::printer("The file $fullPath was created" . PHP_EOL, "light_green");
            file_put_contents($fullPath, $callable);
        } else {
            Cli::printer("The file $fullPath is already exists" . PHP_EOL, "light_yellow");
        }
    }
}
