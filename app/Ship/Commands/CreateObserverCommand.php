<?php

namespace App\Ship\Commands;

use Rudra\Cli\ConsoleFacade as Cli;
use Rudra\Container\Facades\Rudra;

class CreateObserverCommand
{
    /**
     * Creates a file with Seed data
     * -----------------------------
     * Создает файл с данными Seed
     */
    public function actionIndex()
    {
        Cli::printer("Enter observer name: ", "magneta");
        $prefix    = str_replace(PHP_EOL, "", Cli::reader());
        $className = ucfirst($prefix) . 'Observer';

        Cli::printer("Enter container: ", "magneta");
        $container = ucfirst(str_replace(PHP_EOL, "", Cli::reader()));

        if (!empty($container)) {

            $this->writeFile(
                [str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Containers/$container/Observers/"), "{$className}.php"],
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

namespace App\Containers\\{$container}\Observers;

use Rudra\EventDispatcher\ObserverInterface;

class {$className} implements ObserverInterface
{
    public function onEvent()
    {

    }
}
EOT;
    }

    /**
     * @param $path
     * @param $callable
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
