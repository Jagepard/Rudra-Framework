<?php

namespace App\Ship\Command;

use App\Ship\Utils\FileCreator;
use Rudra\Container\Facades\Rudra;
use Rudra\Cli\ConsoleFacade as Cli;

class CreateFactoryCommand extends FileCreator
{
    /**
     * Creates a file with Seed data
     * -----------------------------
     * Создает файл с данными Seed
     */
    public function actionIndex(): void
    {
        Cli::printer("Enter factory name: ", "magneta");
        $prefix    = str_replace(PHP_EOL, "", Cli::reader());
        $className = ucfirst($prefix) . 'Factory';

        Cli::printer("Enter container: ", "magneta");
        $container = ucfirst(str_replace(PHP_EOL, "", Cli::reader()));

        if (!empty($container)) {

            $this->writeFile(
                [str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Containers/$container/Factory/"), "{$className}.php"],
                $this->createClass($className, $container)
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
    private function createClass(string $className, string $container): string
    {
        return <<<EOT
<?php

namespace App\Containers\\{$container}\Factory;

class {$className}
{
    public function create()
    {

    }
}\r\n
EOT;
    }
}
