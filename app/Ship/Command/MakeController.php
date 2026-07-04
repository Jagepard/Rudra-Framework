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

use App\Ship\Utils\FileCreator;
use Rudra\Container\Facades\Rudra;
use Rudra\Cli\ConsoleFacade as Cli;

class MakeController extends FileCreator
{
    /**
     * 🧭 Interactive Controller Generator
     * 
     * CLI wizard that scaffolds a Controller class following Porto architecture.
     * Controllers handle HTTP requests and map them to application logic.
     * 
     * Workflow:
     *  1. Enter controller name  → becomes class name (e.g. "User" → "UserController")
     *  2. Enter container name   → MUST be non-empty (re-prompts otherwise)
     * 
     * Generated file:
     *  - App\Containers\{Name}\Controller\{Name}Controller.php
     * 
     * Additionally, automatically registers the new controller in the routing config.
     * 
     * Validates container existence before writing.
     * 
     * @see self::createClass() for template generation
     * @see self::addRoute()    for automatic route registration
     */
    public function actionIndex(): void
    {
        $controllerPrefix = '';
        $container = '';
        
        // Prompt for controller name until valid input is provided
        while (empty($controllerPrefix)) {
            Cli::printer("🎮 Enter controller name: ", "cyan");
            $controllerPrefix = ucfirst(trim(Cli::reader()));
            
            if (empty($controllerPrefix)) {
                Cli::printer("⚠️  Controller name cannot be empty" . PHP_EOL, "light_yellow");
                continue;
            }
            
            // Validate controller name format (CamelCase)
            if (!preg_match('/^[A-Z][a-zA-Z0-9]*$/', $controllerPrefix)) {
                Cli::printer("❌ Invalid controller name. Use CamelCase (e.g., User, BlogPost)" . PHP_EOL, "light_red");
                $controllerPrefix = '';
                continue;
            }
        }
        
        // Prompt for container name until valid input is provided
        while (empty($container)) {
            Cli::printer("📦 Enter container: ", "cyan");
            $container = ucfirst(trim(Cli::reader()));
            
            if (empty($container)) {
                Cli::printer("⚠️  Container name cannot be empty" . PHP_EOL, "light_yellow");
                continue;
            }
            
            // Validate container name format (CamelCase)
            if (!preg_match('/^[A-Z][a-zA-Z0-9]*$/', $container)) {
                Cli::printer("❌ Invalid container name. Use CamelCase (e.g., User, BlogPost)" . PHP_EOL, "light_red");
                $container = '';
                continue;
            }
        }

        $containerPath = Rudra::config()->get('app.path') . "/app/Containers/$container/";

        // Check if container exists
        if (!is_dir($containerPath)) {
            Cli::printer("⚠️  Container '$container' does not exist" . PHP_EOL, "light_yellow");
            return;
        }

        $controllerPath = $containerPath . "Controller/";
        $controllerFile = "{$controllerPrefix}Controller.php";

        // Check if controller already exists
        if (file_exists($controllerPath . $controllerFile)) {
            Cli::printer("⚠️  Controller '$controllerPrefix' already exists in container '$container'" . PHP_EOL, "light_yellow");
            return;
        }

        // Create controller file
        $this->writeFile(
            [$controllerPath, $controllerFile],
            $this->createClass($controllerPrefix, $container)
        );

        // Register controller routes
        $this->addRoute($container, $controllerPrefix);
        
        Cli::printer("✅ Controller '$controllerPrefix' was created in container '$container'" . PHP_EOL, "light_green");
    }

    private function createClass(string $controllerPrefix, string $container): string
    {
        $url = strtolower("$container/$controllerPrefix");

        if (Rudra::config()->get("attributes")) {
            return <<<EOT
<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace App\Containers\\{$container}\Controller;

use Rudra\Router\Attribute\Routing;
use App\Containers\\{$container}\\{$container}Controller;

class {$controllerPrefix}Controller extends {$container}Controller
{
    #[Routing(url: '{$url}', method: 'GET')]
    public function actionIndex(): void
    {
        dd(__CLASS__);
    }
}
    
EOT;
        }

        return <<<EOT
<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace App\Containers\\{$container}\Controller;

use App\Containers\\{$container}\\{$container}Controller;

class {$controllerPrefix}Controller extends {$container}Controller
{
    /**
     * @Routing(url = '{$url}', method = 'GET')
     */
    public function actionIndex(): void
    {
        dd(__CLASS__);
    }
}

EOT;
    }

    public function addRoute(string $container, string $controllerPrefix): void
    {
        $path   = Rudra::config()->get('app.path') . "/app/Containers/$container/routes.php";
        $routes = require_once $path;
        $namespace = "\App\Containers\\{$container}\\Controller\\{$controllerPrefix}Controller";

        if (!in_array($namespace, $routes)) {
            $contents = file_get_contents($path);
            $contents = str_replace("];", '', $contents);
            file_put_contents($path, $contents);
            $contents = <<<EOT
\t$namespace::class,
];
EOT;
            file_put_contents($path, $contents, FILE_APPEND | LOCK_EX);
        }
    }
}
