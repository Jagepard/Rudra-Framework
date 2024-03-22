<?php

namespace App\Ship\Command;

use App\Ship\Utils\FileCreator;
use Rudra\Container\Facades\Rudra;
use Rudra\Cli\ConsoleFacade as Cli;

class CreateContainerCommand extends FileCreator
{
    /**
     * Creates a file with Seed data
     * -----------------------------
     * Создает файл с данными Seed
     */
    public function actionIndex(): void
    {
        Cli::printer("Enter container name: ", "magneta");
        $container = ucfirst(str_replace(PHP_EOL, "", Cli::reader()));
        $className = $container . 'Controller';

        if (!empty($container)) {

            if (is_dir(str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Containers/$container/"))) {
                Cli::printer("The container $container already exists" . PHP_EOL, "light_yellow");
                return;
            }

            $this->writeFile(
                [str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Containers/$container/"), "{$className}.php"],
                $this->createContainersController($className, $container)
            );

            $this->writeFile(
                [str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Containers/$container/"), "routes.php"],
                $this->createRoutes()
            );

            $this->writeFile(
                [str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Containers/$container/"), "config.php"],
                $this->createConfig()
            );

            $this->addConfig($container);
            $this->createDirectories(str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Containers/$container/"));
            Cli::printer("The container $container was created" . PHP_EOL, "light_green");

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
    private function createContainersController(string $className, string $container): string
    {
        return <<<EOT
<?php

namespace App\Containers\\{$container};

use App\Ship\ShipController;
use Rudra\Container\Facades\Rudra;
use Rudra\View\ViewFacade as View;
use Rudra\Controller\ContainerControllerInterface;

class {$container}Controller extends ShipController implements ContainerControllerInterface
{
    public function containerInit(): void
    {
        \$config = require_once "config.php";

        Rudra::binding()->set(\$config['contracts']);
        Rudra::waiting()->set(\$config['services']);

        View::setup(dirname(__DIR__) . '/', "Web/UI/tmpl", "Web/UI/cache");

        data([
            "title" => __CLASS__,
        ]);
    }
}\r\n
EOT;
    }

    /**
     * Creates routes
     * ------------------
     * Создает файл маршрутизатора
     */
    private function createRoutes(): string
    {
        return <<<EOT
<?php

return [
];\r\n
EOT;
    }

    /**
     * Creates config file
     * -------------------
     * Создает файл конфигурации
     */
    private function createConfig(): string
    {
        return <<<EOT
<?php

return [
    'contracts'   => [

    ],
    'services'    => [

    ]
];\r\n
EOT;
    }

    /**
     * Create UI directories
     * ---------------------
     * Создает каталоги для UI
     *
     * @param string $path
     * @return void
     */
    private function createDirectories(string $path): void
    {
        if (!is_dir($path . 'UI')) {
            mkdir($path . 'UI', 0755, true);
        }

        if (!is_dir($path . 'UI' . DIRECTORY_SEPARATOR . 'cache')) {
            mkdir($path . 'UI' . DIRECTORY_SEPARATOR . 'cache', 0755, true);
        }

        if (!is_dir($path . 'UI' . DIRECTORY_SEPARATOR . 'tmpl')) {
            mkdir($path . 'UI' . DIRECTORY_SEPARATOR . 'tmpl', 0755, true);
        }
    }

    public function addConfig(string $container): void
    {
        $path      = str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/config/setting.local.yml");
        $namespace = strtolower($container) . ": App\Containers\\{$container}\\";
        $contents  = <<<EOT
        \r\n    $namespace
EOT;
        file_put_contents($path, $contents, FILE_APPEND | LOCK_EX);
    }
}
