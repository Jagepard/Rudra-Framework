<?php

namespace App\Commands;

use Rudra\Cli\ConsoleFacade as Cli;
use Rudra\Container\Facades\Rudra;

class CreateSeedCommand
{
    /**
     * Creates a file with Seed data
     * -----------------------------
     * Создает файл с данными Seed
     */
    public function actionIndex()
    {
        Cli::printer("Enter table name: ", "cyan");
        $table     = str_replace("\n", "", Cli::reader());
        $date      = date("_dmYHis");
        $className = ucfirst($table . $date);

        $this->writeFile(
            Rudra::config()->get('app.path') . "/db/Seeds/{$className}_seed.php",
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

namespace Db\Seeds;

use Db\AbstractSeed;

class {$className}_seed extends AbstractSeed
{
    public function create()
    {
        \$table = "$table";
        \$fields = [
            
        ];

        \$this->execute(\$table, \$fields);
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
        if (!file_exists($path)) {
            Cli::printer("The file ", "blue");
            Cli::printer($path, "light_green");
            Cli::printer(" was created\n", "blue");
            file_put_contents($path, $callable);
        }
    }
}
