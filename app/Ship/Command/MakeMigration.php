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
        $table = '';
        $container = '';
        
        // Prompt for table name until valid input is provided
        while (empty($table)) {
            Cli::printer("🗄️  Enter table name: ", "cyan");
            $table = trim(Cli::reader());
            
            if (empty($table)) {
                Cli::printer("⚠️  Table name cannot be empty" . PHP_EOL, "light_yellow");
                continue;
            }
            
            // Validate table name format (snake_case or CamelCase)
            if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $table)) {
                Cli::printer("❌ Invalid table name. Use alphanumeric characters (e.g., users, blog_posts)" . PHP_EOL, "light_red");
                $table = '';
                continue;
            }
        }
        
        // Prompt for container name (optional)
        Cli::printer("📦 Enter container (empty for Ship): ", "cyan");
        $container = ucfirst(trim(Cli::reader()));
        
        // Validate container name if provided
        if (!empty($container) && !preg_match('/^[A-Z][a-zA-Z0-9]*$/', $container)) {
            Cli::printer("❌ Invalid container name. Use CamelCase (e.g., User, BlogPost)" . PHP_EOL, "light_red");
            return;
        }

        // Generate timestamp and class name
        $date = date("_dmYHis");
        $className = ucfirst($table) . $date;

        // Determine target path and namespace based on container
        if (!empty($container)) {
            $targetPath = Rudra::config()->get('app.path') . "/app/Containers/$container/Migration/";
            $namespace = "App\\Containers\\$container\\Migration";
            
            // Check if container exists
            if (!is_dir(Rudra::config()->get('app.path') . "/app/Containers/$container/")) {
                Cli::printer("⚠️  Container '$container' does not exist" . PHP_EOL, "light_yellow");
                return;
            }
        } else {
            $targetPath = Rudra::config()->get('app.path') . "/app/Ship/Migration/";
            $namespace = "App\\Ship\\Migration";
        }

        $targetFile = "{$className}_migration.php";

        // Create migration file
        $this->writeFile(
            [$targetPath, $targetFile],
            $this->createMigration($className, $table, $namespace)
        );
        
        $location = !empty($container) ? "container '$container'" : "Ship";
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
                ->created_at()
                ->updated_at()
                ->pk('id');
        })->execute();
    }
}\r\n
EOT;
    }
}
