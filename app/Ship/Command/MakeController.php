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
        Cli::printer("Enter controller name: ", "magneta");
        $controllerPrefix = trim(ucfirst(str_replace(PHP_EOL, "", Cli::reader())));

        Cli::printer("Enter container: ", "magneta");
        $container = ucfirst(str_replace(PHP_EOL, "", Cli::reader()));

        if (!empty($container)) {
            if (!is_dir(Rudra::config()->get('app.path') . "/app/Containers/$container/")) {
                Cli::printer("⚠️  Container '$container' does not exist" . PHP_EOL, "light_yellow");
                return;
            }
            
            $this->writeFile(
                [Rudra::config()->get('app.path') . "/app/Containers/$container/Controller/", "{$controllerPrefix}Controller.php"],
                $this->createClass($controllerPrefix, $container)
            );

            $this->addRoute($container, $controllerPrefix);
        } else {
            $this->actionIndex();
        }
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
