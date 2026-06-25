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

class MakeContainer extends FileCreator
{
    /**
     * 📦 Interactive Container Generator
     * 
     * CLI wizard that scaffolds a complete Container (module) following Porto architecture.
     * This is a meta-generator: it creates the entire directory structure and boilerplate files.
     * 
     * Workflow:
     *  1. Enter container name → becomes module directory (e.g. "Demo" → "App\Containers\Demo")
     *  2. Name MUST be non-empty (re-prompts otherwise)
     * 
     * Generated structure:
     *  - App\Containers\{Name}\{Name}Controller.php (default controller)
     *  - App\Containers\{Name}\routes.php           (route definitions)
     *  - App\Containers\{Name}\config.php           (container config)
     *  - Standard subdirectories (Entity, Repository, etc.)
     * 
     * Additionally, automatically registers the new container in the global app config.
     * Validates that the container does not already exist to prevent overwriting.
     * 
     * @see self::createContainersController() for default controller template
     * @see self::createRoutes()               for routes template
     * @see self::createConfig()               for config template
     * @see self::addConfig()                  for global registration
     * @see self::createDirectories()          for standard folder scaffolding
     */
    public function actionIndex(): void
    {
        Cli::printer("Enter container name: ", "magneta");
        $container = ucfirst(str_replace(PHP_EOL, "", Cli::reader()));
        $className = trim($container . 'Controller');

        if (!empty($container)) {
            if (is_dir(Rudra::config()->get('app.path') . "/app/Containers/$container/")) {
                Cli::printer("⚠️  Container '$container' already exists" . PHP_EOL, "light_yellow");
                return;
            }

            $this->writeFile(
                [Rudra::config()->get('app.path') . "/app/Containers/$container/", "{$className}.php"],
                $this->createContainersController($className, $container)
            );

            $this->writeFile(
                [Rudra::config()->get('app.path') . "/app/Containers/$container/", "routes.php"],
                $this->createRoutes()
            );

            $this->writeFile(
                [Rudra::config()->get('app.path') . "/app/Containers/$container/", "config.php"],
                $this->createConfig()
            );

            $this->addConfig($container);
            $this->createDirectories(Rudra::config()->get('app.path') . "/app/Containers/$container/");
            Cli::printer("✅ Container '$container' was created" . PHP_EOL, "light_green");

        } else {
            $this->actionIndex();
        }
    }

    private function createContainersController(string $className, string $container): string
    {
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

namespace App\Containers\\{$container};

use App\Ship\ShipController;
use Rudra\Container\Facades\Rudra;
use Rudra\View\ViewFacade as View;
use Rudra\Controller\ContainerControllerInterface;

class {$container}Controller extends ShipController implements ContainerControllerInterface
{
    public function containerInit(): void
    {
        \$config = require_once "config.php";

        Rudra::binding()->set(\$config['contracts']);
        Rudra::waiting()->set(\$config['services']);

        View::setup(dirname(__DIR__) . "/{$container}/UI/tmpl", "{$container}_");

        data([
            "title" => __CLASS__,
        ]);
    }
}\r\n
EOT;
    }

    private function createRoutes(): string
    {
        return <<<EOT
<?php

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

return [
];\r\n
EOT;
    }

    private function createConfig(): string
    {
        return <<<EOT
<?php

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

return [
    'contracts' => [

    ],
    'services'  => [

    ]
];\r\n
EOT;
    }

    private function createDirectories(string $path): void
    {
        if (!is_dir($path . 'UI')) {
            mkdir($path . 'UI', 0755, true);
        }

        if (!is_dir($path . 'UI/tmpl')) {
            mkdir($path . 'UI/tmpl', 0755, true);
        }
    }

    public function addConfig(string $container): void
    {
        $path      = Rudra::config()->get('app.path') . "/config/setting.local.yml";
        $namespace = strtolower($container) . ": App\Containers\\{$container}\\";
        $contents  = <<<EOT
        \r\n    $namespace
EOT;
        file_put_contents($path, $contents, FILE_APPEND | LOCK_EX);
    }
}
