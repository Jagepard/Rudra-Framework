<?php

namespace App\Ship\Command;

use App\Ship\Utils\FileCreator;
use Rudra\Container\Facades\Rudra;
use Rudra\Cli\ConsoleFacade as Cli;

class CreateInterfaceCommand extends FileCreator
{
    /**
     * Creates a file with Seed data
     * -----------------------------
     * Создает файл с данными Seed
     */
    public function actionIndex(): void
    {
        Cli::printer("Enter interface name: ", "magneta");
        $prefix    = str_replace(PHP_EOL, "", Cli::reader());
        $className = ucfirst($prefix) . 'Interface';

        Cli::printer("Enter container: ", "magneta");
        $container = ucfirst(str_replace(PHP_EOL, "", Cli::reader()));

        if (!empty($container)) {

            $this->writeFile(
                [str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Containers/$container/Interface/"), "{$className}.php"],
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

namespace App\Containers\\{$container}\Interface;

interface {$className}
{

}\r\n
EOT;
    }
}
