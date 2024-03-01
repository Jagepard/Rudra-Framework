<?php

namespace App\Ship\Command;

use App\Ship\Utils\FileCreator;
use Rudra\Container\Facades\Rudra;
use Rudra\Cli\ConsoleFacade as Cli;

class CreateModelCommand extends FileCreator
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
                [str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Containers/$container/Repository/"), "{$className}Repository.php"],
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

namespace App\Containers\\{$container}\Model;

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

class {$className}Repository extends Repository
{

}\r\n
EOT;
    }
}
