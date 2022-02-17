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
        $controllerPrefix = str_replace("\n", "", Cli::reader());
        $className        = ucfirst($controllerPrefix) . 'Controller';

        Cli::printer("Enter container: ", "magneta");
        $container = ucfirst(str_replace("\n", "", Cli::reader()));

        if (!empty($container)) {

            $this->writeFile(
                [Rudra::config()->get('app.path') . "/app/Containers/$container/Controllers/", "{$className}.php"],
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
        return <<<EOT
<?php

namespace App\Containers\\{$container}\Controllers;

use App\Containers\\{$container}\\{$container}Controller;

class {$className} extends {$container}Controller
{
    /**
     * @Routing(url = 'your/url', method = 'GET')
     */
    public function actionIndex()
    {

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
            Cli::printer(" was created\n", "light_green");
            file_put_contents($fullPath, $data);
        } else {
            Cli::printer("The file $fullPath is already exists\n", "light_yellow");
        }
    }
}
