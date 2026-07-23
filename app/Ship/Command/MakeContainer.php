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
    use CamelCaseInputTrait;

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
        $container     = $this->getValidCamelCaseName("📦 Enter container name: ", "Container");
        $containerPath = Rudra::config()->get('app.path') . "/app/Containers/$container/";

        if (is_dir($containerPath)) {
            Cli::printer("⚠️  Container '$container' already exists" . PHP_EOL, "light_yellow");
            return;
        }

        $this->createDirectories($containerPath);

        $className = $container . 'Controller';
        $files = [
            "{$className}.php" => $this->createContainersController($container),
            "routes.php"       => $this->createRoutes(),
            "config.php"       => $this->createConfig(strtolower($container)),
        ];

        foreach ($files as $filename => $content) {
            $this->writeFile([$containerPath, $filename], $content);
        }

        $this->addConfig($container);
        
        Cli::printer("✅ Container '$container' was created" . PHP_EOL, "light_green");
        Cli::printer("📝 Registered in config/setting.local.yml" . PHP_EOL, "light_green");
        // Reminder about other environments that need manual update
        Cli::printer(PHP_EOL . "💡 Don't forget to register it in other environments if needed:" . PHP_EOL, "light_cyan");
        Cli::printer("   • config/setting.ddev.yml" . PHP_EOL, "light_cyan");
        Cli::printer("   • config/setting.production.yml" . PHP_EOL . PHP_EOL, "light_cyan");
    
    }

    protected function createContainersController(string $container): string
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
    #[\Override]
    public function containerInit(): void
    {
        \$config = require_once "config.php";

        Rudra::config()->set(\$config);
        Rudra::binding()->set(\$config['contracts']);
        Rudra::waiting()->set(\$config['services']);

        View::setup(dirname(__DIR__) . "/{$container}/UI/tmpl", "{$container}_");

        data([
            "title" => __CLASS__,
        ]);
    }
}
  
EOT;
    }

    protected function createRoutes(): string
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
];

EOT;
    }

    protected function createConfig(string $container): string
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
    'contracts' => [],
    'services' => [],
    '{$container}.settings' => [],
];

EOT;
    }

    protected function createDirectories(string $path): void
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
        // Get current environment (from env var or app_env.php)
        $env = getenv('APP_ENV');
        if ($env === false || $env === '') {
            $envFile = Rudra::config()->get('app.path') . '/app_env.php';
            $env = file_exists($envFile) ? require $envFile : 'local';
        }

        // Path to environment-specific config file
        $path = Rudra::config()->get('app.path') . "/config/setting.{$env}.yml";

        if (!file_exists($path)) {
            file_put_contents($path, "containers:\r\n", LOCK_EX);
        }

        $namespace = strtolower($container) . ": App\Containers\\{$container}\\";
        $contents  = "\r\n    {$namespace}";

        if (str_contains(file_get_contents($path), $namespace)) {
            return;
        }

        file_put_contents($path, $contents, FILE_APPEND | LOCK_EX);
    }
}
