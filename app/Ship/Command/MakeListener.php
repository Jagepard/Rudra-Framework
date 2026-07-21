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
    use CamelCaseInputTrait;

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
        $prefix = $this->getValidCamelCaseName("🎧 Enter listener name: ", "Listener");
        $container = $this->getValidCamelCaseName("📦 Enter container: ", "Container");

        $className = $prefix . 'Listener';
        $containerPath = Rudra::config()->get('app.path') . "/app/Containers/$container/";

        if (!is_dir($containerPath)) {
            Cli::printer("⚠️  Container '$container' does not exist" . PHP_EOL, "light_yellow");
            return;
        }

        $listenerPath = $containerPath . "Listener/";
        $listenerFile = "{$className}.php";

        if (file_exists($listenerPath . $listenerFile)) {
            Cli::printer("⚠️  Listener '$className' already exists in container '$container'" . PHP_EOL, "light_yellow");
            return;
        }

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
}

EOT;
    }
}
