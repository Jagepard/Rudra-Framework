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
    use CamelCaseInputTrait;

    public function actionIndex(): void
    {
        $className = $this->getValidCamelCaseName("🗄️  Enter entity name: ", "Entity");
        $container = $this->getValidCamelCaseName("📦 Enter container: ", "Container");
        $containerPath = Rudra::config()->get('app.path') . "/app/Containers/$container/";

        if (!is_dir($containerPath)) {
            Cli::printer("⚠️  Container '$container' does not exist" . PHP_EOL, "light_yellow");
            return;
        }

        $entityPath     = $containerPath . "Entity/";
        $repositoryPath = $containerPath . "Repository/";
        $entityFile     = "{$className}.php";
        $repositoryFile = "{$className}Repository.php";

        if (file_exists($entityPath . $entityFile)) {
            Cli::printer("⚠️  Entity '$className' already exists in container '$container'" . PHP_EOL, "light_yellow");
            return;
        }

        if (file_exists($repositoryPath . $repositoryFile)) {
            Cli::printer("⚠️  Repository '{$className}Repository' already exists in container '$container'" . PHP_EOL, "light_yellow");
            return;
        }

        $this->writeFile([$entityPath, $entityFile], $this->createEntity($className, $container));
        $this->writeFile([$repositoryPath, $repositoryFile], $this->createRepository($className, $container));
        
        Cli::printer("✅ Entity '$className' and Repository were created in container '$container'" . PHP_EOL, "light_green");
    }

    protected function createEntity(string $className, string $container): string
    {
        $table = strtolower($className);

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

namespace App\Containers\\{$container}\Entity;

use Rudra\Model\Entity;

/**
 * @see \App\Containers\\$container\Repository\\{$className}Repository
 */
class {$className} extends Entity
{
    public static ?string \$table = "$table";
}

EOT;
    }

    protected function createRepository(string $className, string $container): string
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

namespace App\Containers\\{$container}\Repository;

use Rudra\Model\Repository;

class {$className}Repository extends Repository
{
}

EOT;
    }
}
