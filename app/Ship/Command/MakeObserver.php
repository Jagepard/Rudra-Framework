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
        Cli::printer("Enter observer name: ", "magneta");
        $prefix    = str_replace(PHP_EOL, "", Cli::reader());
        $className = trim(ucfirst($prefix) . 'Observer');

        Cli::printer("Enter container: ", "magneta");
        $container = ucfirst(str_replace(PHP_EOL, "", Cli::reader()));

        if (!empty($container)) {
            if (!is_dir(Rudra::config()->get('app.path') . "/app/Containers/$container/")) {
                Cli::printer("⚠️  Container '$container' does not exist" . PHP_EOL, "light_yellow");
                return;
            }

            $this->writeFile(
                [Rudra::config()->get('app.path') . "/app/Containers/$container/Observer/", "{$className}.php"],
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
