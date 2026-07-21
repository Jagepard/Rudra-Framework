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
    use CamelCaseInputTrait;
    
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
        $prefix = $this->getValidCamelCaseName("🏭 Enter factory name: ", "Factory");
        $container = $this->getValidCamelCaseName("📦 Enter container: ", "Container");

        $className = $prefix . 'Factory';
        $containerPath = Rudra::config()->get('app.path') . "/app/Containers/$container/";

        if (!is_dir($containerPath)) {
            Cli::printer("⚠️  Container '$container' does not exist" . PHP_EOL, "light_yellow");
            return;
        }

        $factoryPath = $containerPath . "Factory/";
        $factoryFile = "{$className}.php";

        if (file_exists($factoryPath . $factoryFile)) {
            Cli::printer("⚠️  Factory '$className' already exists in container '$container'" . PHP_EOL, "light_yellow");
            return;
        }

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
}

EOT;
    }
}
