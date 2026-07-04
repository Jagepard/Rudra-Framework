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

class MakeListener extends FileCreator
{
    /**
     * 👂 Interactive Listener Generator
     * 
     * CLI wizard that scaffolds a Listener class following Porto architecture.
     * Listeners react to events dispatched via the EventDispatcher.
     * 
     * Workflow:
     *  1. Enter listener name  → becomes class name (e.g. "User" → "UserListener")
     *  2. Enter container name → MUST be non-empty (re-prompts otherwise)
     * 
     * Generated file:
     *  - App\Containers\{Name}\Listener\{Name}Listener.php
     * 
     * Validates container existence before writing.
     * 
     * @see self::createClass() for template generation
     */
    public function actionIndex(): void
    {
        $prefix = '';
        $container = '';
        
        // Prompt for listener name until valid input is provided
        while (empty($prefix)) {
            Cli::printer("🎧 Enter listener name: ", "cyan");
            $prefix = trim(Cli::reader());
            
            if (empty($prefix)) {
                Cli::printer("⚠️  Listener name cannot be empty" . PHP_EOL, "light_yellow");
                continue;
            }
            
            // Validate listener name format (CamelCase)
            if (!preg_match('/^[A-Z][a-zA-Z0-9]*$/', ucfirst($prefix))) {
                Cli::printer("❌ Invalid listener name. Use CamelCase (e.g., UserRegistered, OrderShipped)" . PHP_EOL, "light_red");
                $prefix = '';
                continue;
            }
        }
        
        $className = ucfirst($prefix) . 'Listener';
        
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

        $listenerPath = $containerPath . "Listener/";
        $listenerFile = "{$className}.php";

        // Check if listener already exists
        if (file_exists($listenerPath . $listenerFile)) {
            Cli::printer("⚠️  Listener '$className' already exists in container '$container'" . PHP_EOL, "light_yellow");
            return;
        }

        // Create listener file
        $this->writeFile(
            [$listenerPath, $listenerFile],
            $this->createClass($className, $container)
        );
        
        Cli::printer("✅ Listener '$className' was created in container '$container'" . PHP_EOL, "light_green");
    }

    private function createClass(string $className, string $container): string
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

namespace App\Containers\\{$container}\Listener;

class {$className}
{
}\r\n
EOT;
    }
}
