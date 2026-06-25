<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace App\Ship\Command;

use Rudra\Container\Facades\Rudra;
use Rudra\Cli\ConsoleFacade as Cli;
use Rudra\Router\RouterFacade as Router;

class DebugRouter
{
    /**
     * 🗺️ Route List Viewer
     * 
     * CLI command that displays all registered routes grouped by their Container.
     * 
     * How it works:
     * 1. Simulates a GET request context ($_SERVER) to trigger the router's parsing mechanism.
     * 2. Iterates through all registered containers.
     * 3. Renders a formatted, colorized ASCII table for each container.
     * 
     * Table columns:
     *  - #         : Route index
     *  - Route     : URL pattern
     *  - Method    : HTTP method (GET, POST, etc.)
     *  - Controller: Fully qualified controller class name
     *  - Action    : Method name inside the controller
     * 
     * @see self::getRoutes() for route extraction logic
     * @see self::getTable()  for table row rendering
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
     * 🔍 Filtered Route List (by Container)
     * 
     * CLI command that displays routes for a specific container only.
     * Interactive version of the full route list — prompts for container name.
     * 
     * How it works:
     * 1. Simulates a GET request context ($_SERVER) to trigger router parsing.
     * 2. Prompts user to enter the target container name.
     * 3. Renders a formatted, colorized ASCII table for that container only.
     * 
     * Table columns:
     *  - #         : Route index
     *  - Route     : URL pattern
     *  - Method    : HTTP method (GET, POST, etc.)
     *  - Controller: Fully qualified controller class name
     *  - Action    : Method name inside the controller
     * 
     * @see self::actionIndex()  for the full route list (all containers)
     * @see self::getRoutes()    for route extraction logic
     * @see self::getTable()     for table row rendering
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

    protected function getRoutes(string $container): array
    {
        $path = "app/Containers/" . ucfirst($container) . "/routes";

        if (file_exists($path . ".php")) {
            return Router::annotationCollector(require $path . ".php", true, Rudra::config()->get("attributes"));
        }

        return [];
    }
}
