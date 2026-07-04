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

class MakeContract extends FileCreator
{
    /**
     *  Interactive Contract (Interface) Generator
     * 
     * CLI wizard that scaffolds an Interface (Contract) following Porto architecture.
     * Contracts define the strict API that implementations must fulfill, enabling 
     * loose coupling and clean dependency injection.
     * 
     * Workflow:
     *  1. Enter interface name → becomes class name (e.g. "User" → "UserInterface")
     *  2. Enter container name → MUST be non-empty (re-prompts otherwise)
     * 
     * Generated file:
     *  - App\Containers\{Name}\Contract\{Name}Interface.php
     * 
     * Validates container existence before writing.
     * 
     * @see self::createClass() for template generation
     */
    public function actionIndex(): void
    {
        $prefix = '';
        $container = '';
        
        // Prompt for interface name until valid input is provided
        while (empty($prefix)) {
            Cli::printer("📜 Enter interface name: ", "cyan");
            $prefix = trim(Cli::reader());
            
            if (empty($prefix)) {
                Cli::printer("⚠️  Interface name cannot be empty" . PHP_EOL, "light_yellow");
                continue;
            }
            
            // Validate interface name format (CamelCase)
            if (!preg_match('/^[A-Z][a-zA-Z0-9]*$/', ucfirst($prefix))) {
                Cli::printer("❌ Invalid interface name. Use CamelCase (e.g., User, PaymentGateway)" . PHP_EOL, "light_red");
                $prefix = '';
                continue;
            }
        }
        
        $className = ucfirst($prefix) . 'Interface';
        
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

        $contractPath = $containerPath . "Contract/";
        $contractFile = "{$className}.php";

        // Check if interface already exists
        if (file_exists($contractPath . $contractFile)) {
            Cli::printer("⚠️  Interface '$className' already exists in container '$container'" . PHP_EOL, "light_yellow");
            return;
        }

        // Create interface file
        $this->writeFile(
            [$contractPath, $contractFile],
            $this->createClass($className, $container)
        );
        
        Cli::printer("✅ Interface '$className' was created in container '$container'" . PHP_EOL, "light_green");
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

namespace App\Containers\\{$container}\Contract;

interface {$className}
{
}\r\n
EOT;
    }
}
