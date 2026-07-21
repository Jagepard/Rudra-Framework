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

class MakeSeed extends FileCreator
{
    use CamelCaseInputTrait;

    /**
     * 🌱 Interactive Seed Generator
     * 
     * Creates a new seed file via CLI prompts:
     * 1. Asks for table name (used in class name + filename)
     * 2. Asks for container name (empty = Ship level)
     * 3. Asks for multiline format preference
     * 
     * Generated file: {ClassName}_{date}_seed.php
     * Location: App\Containers\{Name}\Seed\ or App\Ship\Seed\
     */
    public function actionIndex(): void
    {
        $table = $this->getValidTableName("🌱 Enter table name: ");
        
        Cli::printer("📦 Enter container (empty for Ship): ", "cyan");
        $container = ucfirst(trim(Cli::reader()));
        
        if (!empty($container) && !preg_match('/^[A-Z][a-zA-Z0-9]*$/', $container)) {
            Cli::printer("❌ Invalid container name. Use CamelCase (e.g., User, BlogPost)" . PHP_EOL, "light_red");
            return;
        }

        Cli::printer("📝 Multiline seed [y/N]: ", "cyan");
        $multilineInput = strtolower(trim(Cli::reader()));
        $multiline = in_array($multilineInput, ['yes', 'y'], true);

        $date = date("_YmdHis");
        $className = ucfirst($table) . $date;
        $targetFile = "{$className}_seed.php";

        if (!empty($container)) {
            $basePath = Rudra::config()->get('app.path') . "/app/Containers/$container/";
            
            if (!is_dir($basePath)) {
                Cli::printer("⚠️  Container '$container' does not exist" . PHP_EOL, "light_yellow");
                return;
            }
            
            $targetPath = $basePath . "Seed/";
            $namespace = "App\\Containers\\$container\\Seed";
            $location = "container '$container'";
        } else {
            $targetPath = Rudra::config()->get('app.path') . "/app/Ship/Seed/";
            $namespace = "App\\Ship\\Seed";
            $location = "Ship";
        }

        $this->writeFile(
            [$targetPath, $targetFile],
            $this->createClass($className, $table, $namespace, $multiline)
        );
        
        $mode = $multiline ? "multiline" : "single-line";
        Cli::printer("✅ $mode Seed for table '$table' was created in $location" . PHP_EOL, "light_green");
    }

    private function createClass(string $className, string $table, string $namespace, bool $multiline = false): string
    {
        if ($multiline) {
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

namespace {$namespace};

use App\Ship\Seed\AbstractSeed;

class {$className}_seed extends AbstractSeed
{
    #[\Override]
    public function create(): void
    {
        \$table = "$table";

        \$fieldsArray = [
            [
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
        ];

        foreach (\$fieldsArray as \$fields) {
            \$this->execute(\$table, \$fields);
        }
    }
}

EOT;
        } else {
            return <<<EOT
<?php declare(strict_types=1);

namespace {$namespace};

use App\Ship\Seed\AbstractSeed;

class {$className}_seed extends AbstractSeed
{
    #[\Override]
    public function create(): void
    {
        \$table = "$table";
        \$fields = [
            "created_at" => date('Y-m-d H:i:s'),
            "updated_at" => date('Y-m-d H:i:s'),
        ];

        \$this->execute(\$table, \$fields);
    }
}

EOT;
        }
    }
}
