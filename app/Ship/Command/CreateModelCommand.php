<?php

namespace App\Ship\Commands;

use Rudra\Cli\ConsoleFacade as Cli;
use Rudra\Container\Facades\Rudra;

class CreateModelCommand
{
    /**
     * Creates a file with Seed data
     * -----------------------------
     * Создает файл с данными Seed
     */
    public function actionIndex()
    {
        Cli::printer("Enter table name: ", "magneta");
        $prefix    = str_replace(PHP_EOL, "", Cli::reader());
        $className = ucfirst($prefix);

        Cli::printer("Enter container: ", "magneta");
        $container = ucfirst(str_replace(PHP_EOL, "", Cli::reader()));

        if (!empty($container)) {

            $this->writeFile(
                [str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Containers/$container/Models/"), "{$className}.php"],
                $this->createClass($className, $container)
            );

        } else {
            $this->actionIndex();
        }
    }

    /**
     * @param string $className
     * @param string $container
     * @return string
     *
     * Creates class data
     * ------------------
     * Создает данные класса
     */
    private function createClass(string $className, string $container)
    {
        $table = strtolower($className);

        return <<<EOT
<?php

namespace App\Containers\\{$container}\Models;

use Rudra\Model\Model;

/**
 * @method static yourMethod()
 *
 * @see {$className}Repository
 */
class {$className} extends Model
{
    public static string \$table = "$table";
    public static string \$directory = __DIR__;
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