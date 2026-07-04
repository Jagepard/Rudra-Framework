<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jageard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace App\Ship\Command;

use Rudra\Container\Facades\Rudra;
use Rudra\Cli\ConsoleFacade as Cli;
use Rudra\Router\RouterFacade as Router;

class DebugRouter
{
    // Table formatting constants
    private const MASK  = "| %-3s | %-45s | %-6s | %-65s | %-25s |" . PHP_EOL;
    private const FRAME = "+-----+-----------------------------------------------+--------+-------------------------------------------------------------------+---------------------------+" . PHP_EOL;

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
     * @see self::getRoutes()      for route extraction logic
     * @see self::renderTable()    for table row rendering
     */
    public function actionIndex(): void
    {
        $this->simulateRequestContext();

        foreach (Rudra::config()->get('containers') as $container => $item) {
            $this->renderContainerRoutes($container);
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
     * @see self::actionIndex()    for the full route list (all containers)
     * @see self::getRoutes()      for route extraction logic
     * @see self::renderTable()    for table row rendering
     */
    public function actionContainer(): void
    {
        $this->simulateRequestContext();

        // Prompt for container name
        Cli::printer("📦 Enter container name: ", "cyan");
        $container = ucfirst(trim(Cli::reader()));

        // Validate container name format
        if (empty($container) || !preg_match('/^[A-Z][a-zA-Z0-9]*$/', $container)) {
            Cli::printer("❌ Invalid container name. Use CamelCase (e.g., User, BlogPost)" . PHP_EOL, "light_red");
            return;
        }

        $this->renderContainerRoutes($container);
    }

    /**
     * Renders routes for a specific container in a formatted table.
     */
    protected function renderContainerRoutes(string $container): void
    {
        $routes = $this->getRoutes($container);

        // Display container header
        echo PHP_EOL;
        Cli::printer("📦 " . strtoupper($container) . PHP_EOL, "light_yellow");
        echo PHP_EOL;

        // Render table frame and header
        Cli::printer(self::FRAME, "blue");
        Cli::printer(sprintf(self::MASK, "#", "Route", "Method", "Controller", "Action"), "white", "blue");
        Cli::printer(self::FRAME, "blue");

        // Render data rows or empty message
        if (empty($routes)) {
            Cli::printer("ℹ️  No routes found for container '$container'" . PHP_EOL, "cyan");
        } else {
            $this->renderTable($routes);
        }

        Cli::printer(self::FRAME, "blue");
    }

    /**
     * Renders route data as colorized table rows.
     * Uses alternating colors (cyan/green) for better readability.
     */
    protected function renderTable(array $data): void
    {
        $i = 1;
        $colors = ["cyan", "green"]; // alternating row colors

        foreach ($data as $routes) {
            foreach ($routes as $route) {
                $color = $colors[($i - 1) % 2];
                $row = sprintf(
                    self::MASK,
                    $i,
                    $route['url'],
                    $route['method'],
                    $route['controller'],
                    $route['action'] ?? 'actionIndex'
                );
                Cli::printer($row, $color);
                $i++;
            }
        }
    }

    /**
     * Simulates GET request context to trigger router parsing.
     */
    protected function simulateRequestContext(): void
    {
        $_SERVER["REQUEST_METHOD"] = 'GET';
        $_SERVER["REQUEST_URI"]    = '';
    }

    /**
     * Extracts routes from container's routes file.
     * 
     * @param string $container Container name (CamelCase)
     * @return array            Array of routes grouped by HTTP method
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
