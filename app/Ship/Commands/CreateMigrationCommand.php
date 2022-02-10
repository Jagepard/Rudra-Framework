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
        $table = str_replace("\n", "", Cli::reader());

        Cli::printer("Enter container (empty for Ship): ", "magneta");
        $container = ucfirst(str_replace("\n", "", Cli::reader()));

        $date      = date("_dmYHis");
        $className = ucfirst($table) . $date;

        if (!empty($container)) {
            $this->createFile([Rudra::config()->get('app.path') . "/app/Containers/" . $container . "/Migrations/", "{$className}_migration.php"],
                $this->createContainersClass($className, $container, $table)
            );
        } else {
            $this->createFile([Rudra::config()->get('app.path') . "/app/Ship/Migrations/", "{$className}_migration.php"],
                $this->createShipClass($className, $table)
            );
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
    private function createContainersClass(string $className, string $container, string $table)
    {
        return <<<EOT
<?php

namespace App\Containers\\$container\\Migrations;

use Rudra\Container\Facades\Rudra;

class {$className}_migration
{
    public function up()
    {
        \$table = "$table";

        \$query = Rudra::get("DSN")->prepare("
            CREATE TABLE {\$table} (
            `id` INT NOT NULL AUTO_INCREMENT ,
            , PRIMARY KEY (`id`)) ENGINE = InnoDB
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
    private function createShipClass(string $className, string $table)
    {
        return <<<EOT
<?php

namespace App\Ship\\Migrations;

use Rudra\Container\Facades\Rudra;

class {$className}_migration
{
    public function up()
    {
        \$table = "$table";

        \$query = Rudra::get("DSN")->prepare("
            CREATE TABLE {\$table} (
            `id` INT NOT NULL AUTO_INCREMENT ,
            , PRIMARY KEY (`id`)) ENGINE = InnoDB
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
    private function createFile($path, $callable)
    {
        if (!is_dir($path[0])) {
            mkdir($path[0], 0755, true);
        }

        $fullPath = $path[0] . $path[1];

        if (!file_exists($fullPath)) {
            Cli::printer("The file $fullPath was created\n", "blue");
            file_put_contents($fullPath, $callable);
        } else {
            Cli::printer("The file $fullPath is already exists", "light_green");
        }
    }
}
