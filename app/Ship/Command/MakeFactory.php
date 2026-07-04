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

class MakeFactory extends FileCreator
{
    /**
     * 🏭 Interactive Factory Generator
     * 
     * CLI wizard that scaffolds a Factory class following Porto architecture.
     * Factories encapsulate complex object creation and instantiation logic.
     * 
     * Workflow:
     *  1. Enter factory name   → becomes class name (e.g. "User" → "UserFactory")
     *  2. Enter container name → MUST be non-empty (re-prompts otherwise)
     * 
     * Generated file:
     *  - App\Containers\{Name}\Factory\{Name}Factory.php
     * 
     * Validates container existence before writing.
     * 
     * @see self::createClass() for template generation
     */
    public function actionIndex(): void
    {
        $prefix = '';
        $container = '';
        
        // Prompt for factory name until valid input is provided
        while (empty($prefix)) {
            Cli::printer("🏭 Enter factory name: ", "cyan");
            $prefix = trim(Cli::reader());
            
            if (empty($prefix)) {
                Cli::printer("⚠️  Factory name cannot be empty" . PHP_EOL, "light_yellow");
                continue;
            }
            
            // Validate factory name format (CamelCase)
            if (!preg_match('/^[A-Z][a-zA-Z0-9]*$/', ucfirst($prefix))) {
                Cli::printer("❌ Invalid factory name. Use CamelCase (e.g., User, Payment)" . PHP_EOL, "light_red");
                $prefix = '';
                continue;
            }
        }
        
        $className = ucfirst($prefix) . 'Factory';
        
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

        $factoryPath = $containerPath . "Factory/";
        $factoryFile = "{$className}.php";

        // Check if factory already exists
        if (file_exists($factoryPath . $factoryFile)) {
            Cli::printer("⚠️  Factory '$className' already exists in container '$container'" . PHP_EOL, "light_yellow");
            return;
        }

        // Create factory file
        $this->writeFile(
            [$factoryPath, $factoryFile],
            $this->createClass($className, $container)
        );
        
        Cli::printer("✅ Factory '$className' was created in container '$container'" . PHP_EOL, "light_green");
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

namespace App\Containers\\{$container}\Factory;

use Rudra\Container\Interfaces\FactoryInterface;

class {$className} implements FactoryInterface
{
    #[\Override]
    public function create(): object
    {
    }
}\r\n
EOT;
    }
}
