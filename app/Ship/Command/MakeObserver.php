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

class MakeObserver extends FileCreator
{
    /**
     * 👁️ Interactive Observer Generator
     * 
     * Creates a new Observer class via CLI prompts:
     * 1. Asks for observer name (becomes {Name}Observer)
     * 2. Asks for container name (required — re-prompts if empty)
     * 
     * Generated file: {Name}Observer.php
     * Location: App\Containers\{Name}\Observer\
     */
    public function actionIndex(): void
    {
        $prefix = '';
        $container = '';
        
        // Prompt for observer name until valid input is provided
        while (empty($prefix)) {
            Cli::printer("👁️  Enter observer name: ", "cyan");
            $prefix = trim(Cli::reader());
            
            if (empty($prefix)) {
                Cli::printer("⚠️  Observer name cannot be empty" . PHP_EOL, "light_yellow");
                continue;
            }
            
            // Validate observer name format (CamelCase)
            if (!preg_match('/^[A-Z][a-zA-Z0-9]*$/', ucfirst($prefix))) {
                Cli::printer("❌ Invalid observer name. Use CamelCase (e.g., UserObserver, OrderObserver)" . PHP_EOL, "light_red");
                $prefix = '';
                continue;
            }
        }
        
        $className = ucfirst($prefix) . 'Observer';
        
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

        $observerPath = $containerPath . "Observer/";
        $observerFile = "{$className}.php";

        // Check if observer already exists
        if (file_exists($observerPath . $observerFile)) {
            Cli::printer("⚠️  Observer '$className' already exists in container '$container'" . PHP_EOL, "light_yellow");
            return;
        }

        // Create observer file
        $this->writeFile(
            [$observerPath, $observerFile],
            $this->createClass($className, $container)
        );
        
        Cli::printer("✅ Observer '$className' was created in container '$container'" . PHP_EOL, "light_green");
    }

    private function createClass(string $className, string $container): string
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

namespace App\Containers\\{$container}\Observer;

use Rudra\EventDispatcher\ObserverInterface;

class {$className} implements ObserverInterface
{
    public function onEvent()
    {
    }
}\r\n
EOT;
    }
}
