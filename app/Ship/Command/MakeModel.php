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

class MakeModel extends FileCreator
{
    /**
     * 🧱 Interactive Model Generator (Entity + Repository)
     * 
     * CLI wizard that scaffolds a Model following Porto architecture.
     * Automatically generates both the Entity and its corresponding Repository.
     * 
     * Workflow:
     *  1. Enter table name     → becomes Entity class name (e.g. "user" → "User")
     *  2. Enter container name → MUST be non-empty (re-prompts otherwise)
     * 
     * Generated files:
     *  - App\Containers\{Name}\Entity\{Name}.php
     *  - App\Containers\{Name}\Repository\{Name}Repository.php
     * 
     * Validates container existence before writing.
     * 
     * @see self::createEntity()     for Entity template generation
     * @see self::createRepository() for Repository template generation
     */
    public function actionIndex(): void
    {
        Cli::printer("Enter table name: ", "magneta");
        $prefix    = str_replace(PHP_EOL, "", Cli::reader());
        $className = trim(ucfirst($prefix));

        Cli::printer("Enter container: ", "magneta");
        $container = ucfirst(str_replace(PHP_EOL, "", Cli::reader()));

        if (!empty($container)) {
            if (!is_dir(Rudra::config()->get('app.path') . "/app/Containers/$container/")) {
                Cli::printer("⚠️  Container '$container' does not exist" . PHP_EOL, "light_yellow");
                return;
            }

            $this->writeFile(
                [Rudra::config()->get('app.path') . "/app/Containers/$container/Entity/", "{$className}.php"],
                $this->createEntity($className, $container)
            );

            $this->writeFile(
                [Rudra::config()->get('app.path') . "/app/Containers/$container/Repository/", "{$className}Repository.php"],
                $this->createRepository($className, $container)
            );
        } else {
            $this->actionIndex();
        }
    }

    private function createEntity(string $className, string $container): string
    {
        $table = strtolower($className);

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

namespace App\Containers\\{$container}\Entity;

use Rudra\Model\Entity;

/**
 * @see \App\Containers\\$container\Repository\\{$className}Repository
 */
class {$className} extends Entity
{
    public static string \$table = "$table";
}\r\n
EOT;
    }

    private function createRepository(string $className, string $container): string
    {
        $table = strtolower($className);

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

namespace App\Containers\\{$container}\Repository;

use Rudra\Model\Repository;

class {$className}Repository extends Repository
{

}\r\n
EOT;
    }
}
