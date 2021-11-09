<?php

namespace App\Commands;

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
        Cli::printer("Enter table name: ", "cyan");
        $table     = str_replace("\n", "", Cli::reader());
        $date      = date("_dmYHis");
        $className = ucfirst($table) . $date;

        $this->createFile(
            Rudra::config()->get('app.path') . "/db/Migrations/{$className}_migration.php",
            $this->createClass($className, $table)
        );
    }

    /**
     * @param string $className
     * @param string $table
     * @return string
     *
     * Creates class data
     * ------------------
     * Создает данные класса
     */
    private function createClass(string $className, string $table)
    {
        return <<<EOT
<?php

namespace Db\Migrations;

use Rudra\Container\Facades\Rudra;

class {$className}_migration
{
    public function up()
    {
        \$table = "$table";

        \$query = Rudra::get("MySQL")->prepare("            
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
        if (!file_exists($path)) {
            Cli::printer("The file $path was created", "blue");
            file_put_contents($path, $callable);
        } else {
            Cli::printer("The file $path is already exists", "light_green");
        }
    }
}
