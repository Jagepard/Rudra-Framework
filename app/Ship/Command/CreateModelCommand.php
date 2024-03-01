<?php

namespace App\Ship\Command;

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
                [str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Containers/$container/Entity/"), "{$className}.php"],
                $this->createEntity($className, $container)
            );

            $this->writeFile(
                [str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Containers/$container/Model/"), "{$className}.php"],
                $this->createModel($className, $container)
            );

            $this->writeFile(
                [str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Containers/$container/Repository/"), "{$className}.php"],
                $this->createRepository($className, $container)
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
    private function createEntity(string $className, string $container)
    {
        $table = strtolower($className);

        return <<<EOT
<?php

namespace App\Containers\\{$container}\Entity;

use Rudra\Model\Entity;

/**
 * @see App\Containers\\$container\Repository\\{$className}Repository
 */
class {$className} extends Entity
{
    public static string \$table = "$table";
    public static string \$directory = __DIR__;
}\r\n
EOT;
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
    private function createModel(string $className, string $container)
    {
        $table = strtolower($className);

        return <<<EOT
<?php

namespace App\Containers\\{$container}\Entity;

use Rudra\Model\Model;

class {$className} extends Model
{

}\r\n
EOT;
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
    private function createRepository(string $className, string $container)
    {
        $table = strtolower($className);

        return <<<EOT
<?php

namespace App\Containers\\{$container}\Repository;

use Rudra\Model\QBFacade;
use Rudra\Model\Repository;

class {$className} extends Repository
{

}\r\n
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
