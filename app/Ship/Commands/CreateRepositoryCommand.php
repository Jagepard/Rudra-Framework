<?php

namespace App\Ship\Commands;

use Rudra\Cli\ConsoleFacade as Cli;
use Rudra\Container\Facades\Rudra;

class CreateRepositoryCommand
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
        $className = ucfirst($table) . 'Repository';

        Cli::printer("Enter container: ", "magneta");
        $container = ucfirst(str_replace(PHP_EOL, "", Cli::reader()));

        if (!empty($container)) {

            $this->writeFile(
                [str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Containers/$container/Repository/"), "{$className}.php"],
                $this->createClass($className, $container, $table)
            );

        } else {
            $this->actionIndex();
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
    private function createClass(string $className, string $container, string $table)
    {
        $model = ucfirst($table);

        return <<<EOT
<?php

namespace App\Containers\\{$container}\Repository;

use Rudra\Model\QBFacade;
use App\Containers\Web\Models\\$model;

class {$className}
{
    public static string \$table = "$table";
}
EOT;
    }

    /**
     * @param $path
     * @param $data
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
