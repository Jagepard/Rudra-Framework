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
        Cli::printer("Enter interface name: ", "magneta");
        $prefix    = str_replace(PHP_EOL, "", Cli::reader());
        $className = trim(ucfirst($prefix) . 'Interface');

        Cli::printer("Enter container: ", "magneta");
        $container = ucfirst(str_replace(PHP_EOL, "", Cli::reader()));

        if (!empty($container)) {
            if (!is_dir(Rudra::config()->get('app.path') . "/app/Containers/$container/")) {
                Cli::printer("⚠️  Container '$container' does not exist" . PHP_EOL, "light_yellow");
                return;
            }

            $this->writeFile(
                [Rudra::config()->get('app.path') . "/app/Containers/$container/Contract/", "{$className}.php"],
                $this->createClass($className, $container)
            );

        } else {
            $this->actionIndex();
        }
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
