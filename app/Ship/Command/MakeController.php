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
    use CamelCaseInputTrait;

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
        $controllerPrefix = $this->getValidCamelCaseName("🎮 Enter controller name: ", "Controller");
        $container        = $this->getValidCamelCaseName("📦 Enter container: ", "Container");
        $containerPath    = Rudra::config()->get('app.path') . "/app/Containers/$container/";

        if (!is_dir($containerPath)) {
            Cli::printer("⚠️  Container '$container' does not exist" . PHP_EOL, "light_yellow");
            return;
        }

        $controllerPath = $containerPath . "Controller/";
        $controllerFile = "{$controllerPrefix}Controller.php";

        if (file_exists($controllerPath . $controllerFile)) {
            Cli::printer("⚠️  Controller '{$controllerPrefix}Controller' already exists in container '$container'" . PHP_EOL, "light_yellow");
            return;
        }

        $this->writeFile(
            [$controllerPath, $controllerFile],
            $this->createClass($controllerPrefix, $container)
        );

        $this->addRoute($container, $controllerPrefix);
        
        Cli::printer("✅ Controller '{$controllerPrefix}Controller' was created in container '$container'" . PHP_EOL, "light_green");
    }

    protected function createClass(string $controllerPrefix, string $container): string
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
        $namespace = "App\\Containers\\{$container}\\Controller\\{$controllerPrefix}Controller";

        if (!in_array($namespace, $routes, true)) {
            $contents = file_get_contents($path);
            $contents = str_replace("];", "\t\\$namespace::class,\n];", $contents);
            file_put_contents($path, $contents, LOCK_EX);
        }
    }
}
