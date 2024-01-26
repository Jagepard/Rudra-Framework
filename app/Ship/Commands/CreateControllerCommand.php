<?php

namespace App\Ship\Commands;

use Rudra\Cli\ConsoleFacade as Cli;
use Rudra\Container\Facades\Rudra;

class CreateControllerCommand
{
    /**
     * Creates a file with Seed data
     * -----------------------------
     * Создает файл с данными Seed
     */
    public function actionIndex()
    {
        Cli::printer("Enter controller name: ", "magneta");
        $controllerPrefix = ucfirst(str_replace(PHP_EOL, "", Cli::reader()));

        Cli::printer("Enter container: ", "magneta");
        $container = ucfirst(str_replace(PHP_EOL, "", Cli::reader()));

        if (!empty($container)) {

            $this->writeFile(
                [str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Containers/$container/Controllers/"), "{$controllerPrefix}Controller.php"],
                $this->createClass($controllerPrefix, $container)
            );

            $this->addRoute($container, $controllerPrefix);

        } else {
            $this->actionIndex();
        }
    }

    /**
     * @param string $controllerPrefix
     * @param string $container
     * @return string
     *
     * Creates class data
     * ------------------
     * Создает данные класса
     */
    private function createClass(string $controllerPrefix, string $container)
    {
        $url = strtolower("$container/$controllerPrefix");

        if (Rudra::config()->get("attributes")) {
            return <<<EOT
            <?php
            
            namespace App\Containers\\{$container}\Controllers;
            
            use App\Containers\\{$container}\\{$container}Controller;
            
            class {$controllerPrefix}Controller extends {$container}Controller
            {
                #[Routing(url: '{$url}', method: 'GET')]
                public function actionIndex()
                {
                    dd(__CLASS__);
                }
            }
            EOT;
        }

        return <<<EOT
<?php

namespace App\Containers\\{$container}\Controllers;

use App\Containers\\{$container}\\{$container}Controller;

class {$controllerPrefix}Controller extends {$container}Controller
{
    /**
     * @Routing(url = '{$url}', method = 'GET')
     */
    public function actionIndex()
    {
        dd(__CLASS__);
    }
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

    public function addRoute(string $container, string $controllerPrefix)
    {
        $path   = str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Containers/$container/routes.php");
        $routes = require_once $path;
        $namespace = "\App\Containers\\{$container}\\Controllers\\{$controllerPrefix}Controller";

        if (!in_array($namespace, $routes)) {
            $contents = file_get_contents($path);
            $contents = str_replace("];", '', $contents);
            file_put_contents($path, $contents);
            $contents = <<<EOT
    $namespace::class,
];
EOT;
            file_put_contents($path, $contents, FILE_APPEND | LOCK_EX);
        }
    }
}
