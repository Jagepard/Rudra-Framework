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
    public function actionIndex(): void
    {
        Cli::printer("Enter table name: ", "magneta");
        $prefix    = str_replace(PHP_EOL, "", Cli::reader());
        $className = ucfirst($prefix);

        Cli::printer("Enter container: ", "magneta");
        $container = ucfirst(str_replace(PHP_EOL, "", Cli::reader()));

        if (!empty($container)) {
            if (!is_dir(Rudra::config()->get('app.path') . "/app/Containers/$container/")) {
                Cli::printer("⚠️  Container '$container' does not exist" . PHP_EOL, "light_yellow");
                return;
            }

            $this->writeFile(
                [Rudra::config()->get('app.path') . "/app/Containers/$container/Entity/", "{$className}.php"],
                $this->createEntity($className, $container)
            );

            $this->writeFile(
                [Rudra::config()->get('app.path') . "/app/Containers/$container/Repository/", "{$className}Repository.php"],
                $this->createRepository($className, $container)
            );
        } else {
            $this->actionIndex();
        }
    }

    /**
     * Creates class data
     * ------------------
     * Создает данные класса
     *
     * @param string $className
     * @param string $container
     * @return string
     */
    private function createEntity(string $className, string $container): string
    {
        $table = strtolower($className);

        return <<<EOT
<?php

namespace App\Containers\\{$container}\Entity;

use Rudra\Model\Entity;

/**
 * @see \App\Containers\\$container\Repository\\{$className}Repository
 */
class {$className} extends Entity
{
    public static string \$table = "$table";
}\r\n
EOT;
    }

    /**
     * Creates class data
     * ------------------
     * Создает данные класса
     *
     * @param string $className
     * @param string $container
     * @return string
     */
    private function createRepository(string $className, string $container): string
    {
        $table = strtolower($className);

        return <<<EOT
<?php

namespace App\Containers\\{$container}\Repository;

use Rudra\Model\Repository;

class {$className}Repository extends Repository
{

}\r\n
EOT;
    }
}
