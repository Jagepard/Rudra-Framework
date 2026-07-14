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

class MakeMigration extends FileCreator
{
    use CamelCaseInputTrait;

    /**
     * 🗄️ Interactive Migration Generator
     * 
     * CLI wizard that scaffolds a migration file for a given table.
     * Supports both Container-level and Ship-level migrations (Porto).
     * 
     * Workflow:
     *  1. Enter table name     → becomes part of class name (e.g. "users" → "Users_25062026120000")
     *  2. Enter container name → empty input places migration in App\Ship\Migration\
     * 
     * Generated files:
     *  - Container: App\Containers\{Name}\Migration\{Table}_{date}_migration.php
     *  - Ship:      App\Ship\Migration\{Table}_{date}_migration.php
     * 
     * Timestamp suffix (_dmYHis) preserves creation order and avoids collisions.
     * 
     * @see self::createMigration() for template generation
     */
    public function actionIndex(): void
    {
        $table = $this->getValidTableName("🗄️  Enter table name: ");
        
        Cli::printer("📦 Enter container (empty for Ship): ", "cyan");
        $container = ucfirst(trim(Cli::reader()));
        
        if (!empty($container) && !preg_match('/^[A-Z][a-zA-Z0-9]*$/', $container)) {
            Cli::printer("❌ Invalid container name. Use CamelCase (e.g., User, BlogPost)" . PHP_EOL, "light_red");
            return;
        }

        $date = date("_dmYHis");
        $className = ucfirst($table) . $date;
        $targetFile = "{$className}_migration.php";

        if (!empty($container)) {
            $basePath = Rudra::config()->get('app.path') . "/app/Containers/$container/";
            
            if (!is_dir($basePath)) {
                Cli::printer("⚠️  Container '$container' does not exist" . PHP_EOL, "light_yellow");
                return;
            }
            
            $targetPath = $basePath . "Migration/";
            $namespace = "App\\Containers\\$container\\Migration";
            $location = "container '$container'";
        } else {
            $targetPath = Rudra::config()->get('app.path') . "/app/Ship/Migration/";
            $namespace = "App\\Ship\\Migration";
            $location = "Ship";
        }

        $this->writeFile(
            [$targetPath, $targetFile],
            $this->createMigration($className, $table, $namespace)
        );
        
        Cli::printer("✅ Migration for table '$table' was created in $location" . PHP_EOL, "light_green");
    }

    private function createMigration(string $className, string $table, string $namespace): string
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

namespace $namespace;

use Rudra\Model\Schema;

class {$className}_migration
{
    public function up(): void
    {
        Schema::create('$table', function (\$table) {
            \$table->integer('id', autoincrement: true)
                ->createdAt()
                ->updatedAt()
                ->primaryKey('id');
        })->execute();
    }
}\r\n
EOT;
    }
}
