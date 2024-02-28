<?php

namespace App\Ship\Commands;

use Rudra\Cli\ConsoleFacade as Cli;
use Rudra\Container\Facades\Rudra;
use Rudra\Router\RouterFacade as Router;

class RouterCommand
{
    /**
     * Returns all routes
     * ------------------
     * Возвращает все маршруты
     */
    public function actionIndex()
    {
        $_SERVER["REQUEST_METHOD"] = 'GET';
        $_SERVER["REQUEST_URI"]    = '';

        foreach (Rudra::config()->get('containers') as $container => $item) {
            $mask = "|%-3.3s|%-45.45s|%-7.7s|%-65.65s|%-25.25s| x |" . PHP_EOL;
            Cli::printer(strtoupper($container) . PHP_EOL, "yellow");
            printf("\e[5;35m" . $mask . "\e[m", " ", "route", "method", "controller", "action");
            $this->getTable($this->getRoutes($container));
        }
    }

    /**
     * Returns the route of the module
     * -------------------------------
     * Возвращает маршрут модуля
     */
    public function actionContainer()
    {
        $_SERVER["REQUEST_METHOD"] = 'GET';
        $_SERVER["REQUEST_URI"]    = '';

        Cli::printer("Enter container name: ", "magneta");
        $link = trim(Cli::reader());
        $mask = "|%-3.3s|%-45.45s|%-7.7s|%-65.65s|%-25.25s| x |" . PHP_EOL;
        printf("\e[5;35m" . $mask . "\e[m", " ", "route", "method", "controller", "action");
        $this->getTable($this->getRoutes($link));
    }

    /**
     * @param array $data
     *
     * Forms a table
     * -------------
     * Формирует таблицу
     */
    protected function getTable(array $data)
    {
        $mask = "|%-3.3s|%-45.45s|%-7.7s|%-65.65s|%-25.25s| x |" . PHP_EOL;
        $i    = 1;

        foreach ($data as $routes) {
            //dump($routes); continue;
            printf("\e[5;36m" . $mask . "\e[m", $i, $routes[0][0], $routes[0][1], $routes[0][2][0], $routes[0][2][1] ?? "actionIndex");
            $i++;
        }
    }

    /**
     * @param string $container
     * @return mixed
     *
     * Builds route files from modules
     * -------------------------------
     * Собирает файлы маршрутов из модулей
     */
    protected function getRoutes(string $container) //: array
    {
        $path = "app/Containers/" . ucfirst($container) . "/routes";

        if (file_exists($path . ".php")) {
            $routes = Router::annotationCollector(require $path . ".php", true);
            
            if (count($routes) === 0) {
                return Router::annotationCollector(require $path . ".php", true, true);
            }

            return $routes;
        }
    }
}
