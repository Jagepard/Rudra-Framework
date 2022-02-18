<?php

namespace App\Ship\Commands;

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
        Cli::printer("Enter table name: ", "magneta");
        $table     = str_replace(PHP_EOL, "", Cli::reader());
        $date      = date("_dmYHis");
        $className = ucfirst($table . $date);

        Cli::printer("Enter container (empty for Ship): ", "magneta");
        $container = ucfirst(str_replace(PHP_EOL, "", Cli::reader()));

        if (!empty($container)) {

            $namespace = 'App\Containers\\' . $container . '\Seeds';

            $this->writeFile(
                [str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Containers/$container/Seeds/"), "{$className}_seed.php"],
                $this->createClass($className, $table, $namespace)
            );


        } else {

            $namespace = "App\Ship\Seeds";

            $this->writeFile(
                [str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Ship/Seeds/"), "{$className}_seed.php"],
                $this->createClass($className, $table, $namespace)
            );
        }
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
    private function createClass(string $className, string $table, string $namespace)
    {
        return <<<EOT
<?php

namespace {$namespace};

use App\Ship\Seeds\AbstractSeed;

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
    private function writeFile(array $path, string $data)
    {
        if (!is_dir($path[0])) {
            mkdir($path[0], 0755, true);
        }

        $fullPath = $path[0] . $path[1];

        if (!file_exists($fullPath)) {
            Cli::printer("The file ", "light_green");
            Cli::printer($fullPath, "light_green");
            Cli::printer(" was created" . PHP_EOL, "light_green");
            file_put_contents($fullPath, $data);
        } else {
            Cli::printer("The file $fullPath is already exists" . PHP_EOL, "light_yellow");
        }
    }
}
