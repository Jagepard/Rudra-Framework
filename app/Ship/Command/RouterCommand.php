<?php

namespace App\Ship\Command;

use Rudra\Container\Facades\Rudra;
use Rudra\Cli\ConsoleFacade as Cli;
use Rudra\Router\RouterFacade as Router;

class RouterCommand
{
    /**
     * Returns all routes
     * ------------------
     * Возвращает все маршруты
     */
    public function actionIndex(): void
    {
        $_SERVER["REQUEST_METHOD"] = 'GET';
        $_SERVER["REQUEST_URI"]    = '';

        foreach (Rudra::config()->get('containers') as $container => $item) {
            $mask  = "| %-3s | %-45s | %-6s | %-65s | %-25s |" . PHP_EOL;
            $frame = "\e[1;34m+-----+-----------------------------------------------+--------+-------------------------------------------------------------------+---------------------------+\e[m" . PHP_EOL;
            Cli::printer(strtoupper($container) . PHP_EOL, "yellow");

            echo $frame;
            printf("\e[1;95m" . $mask . "\e[m", "#", "Route", "Method", "Controller", "Action");
            echo $frame;
            $this->getTable($this->getRoutes($container), $mask);
            echo $frame;
        }
    }

    /**
     * Returns the route of the module
     * -------------------------------
     * Возвращает маршрут модуля
     */
    public function actionContainer(): void
    {
        $_SERVER["REQUEST_METHOD"] = 'GET';
        $_SERVER["REQUEST_URI"]    = '';

        Cli::printer("Enter container name: ", "magenta");
        $link  = trim(Cli::reader());
        $mask  = "| %-3s | %-45s | %-6s | %-65s | %-25s |" . PHP_EOL;
        $frame = "\e[1;34m+-----+---------------------------------------------+--------+-------------------------------------------------------------------+--------------------------+\e[m" . PHP_EOL;

        echo $frame;
        printf("\e[1;95m" . $mask . "\e[m", "#", "Route", "Method", "Controller", "Action");
        echo $frame;
        $this->getTable($this->getRoutes($link), $mask);
        echo $frame;
    }

    /**
     * Generates a color-alternating route table
     * -----------------------------------------
     * Формирует таблицу маршрутов с чередованием цветов
     */
    protected function getTable(array $data, string $mask): void
    {
        $i = 1;
        $colors = ["\e[0;36m", "\e[0;32m"]; // color-alternating

        foreach ($data as $routes) {
            foreach ($routes as $route) {
                $color = $colors[($i - 1) % 2];
                printf(
                    $color . $mask . "\e[m",
                    $i,
                    $route['url'],
                    $route['method'],
                    $route['controller'],
                    $route['action'] ?? 'actionIndex'
                );
                $i++;
            }
        }
    }

    /**
     * Collects route files from modules
     * ---------------------------------
     * Собирает файлы маршрутов из модулей
     */
    protected function getRoutes(string $container): array
    {
        $path = "app/Containers/" . ucfirst($container) . "/routes";

        if (file_exists($path . ".php")) {
            return Router::annotationCollector(require $path . ".php", true, Rudra::config()->get("attributes"));
        }

        return [];
    }
}
