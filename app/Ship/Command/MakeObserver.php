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
    use CamelCaseInputTrait;
           
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
        $prefix = $this->getValidCamelCaseName("👁️  Enter observer name: ", "Observer");
        $container = $this->getValidCamelCaseName("📦 Enter container: ", "Container");

        $className = $prefix . 'Observer';
        $containerPath = Rudra::config()->get('app.path') . "/app/Containers/$container/";

        if (!is_dir($containerPath)) {
            Cli::printer("⚠️  Container '$container' does not exist" . PHP_EOL, "light_yellow");
            return;
        }

        $observerPath = $containerPath . "Observer/";
        $observerFile = "{$className}.php";

        if (file_exists($observerPath . $observerFile)) {
            Cli::printer("⚠️  Observer '$className' already exists in container '$container'" . PHP_EOL, "light_yellow");
            return;
        }

        $this->writeFile(
            [$observerPath, $observerFile],
            $this->createClass($className, $container)
        );
        
        Cli::printer("✅ Observer '$className' was created in container '$container'" . PHP_EOL, "light_green");
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

namespace App\Containers\\{$container}\Observer;

use Rudra\EventDispatcher\ObserverInterface;

class {$className} implements ObserverInterface
{
    public function onEvent()
    {
    }
}

EOT;
    }
}
