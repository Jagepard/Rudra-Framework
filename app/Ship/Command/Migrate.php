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

use Rudra\Container\Facades\Rudra;
use Rudra\Cli\ConsoleFacade as Cli;
use App\Ship\Utils\Database\LoggerAdapter;

class Migrate extends LoggerAdapter
{
    public function __construct()
    {
        $this->table = "rudra_migrations";
        parent::__construct();
    }

    /**
     * 🛤️ Interactive Migration Runner
     * 
     * Executes all pending migrations for a given container (or Ship) 
     * and tracks their state.
     * 
     * Workflow:
     *  1. Enter container name → empty input defaults to Ship level
     *  2. Scans the corresponding Migration/ directory for migration files
     *  3. Initializes the `rudra_migrations` tracking table if it doesn't exist
     *  4. Iterates through migrations in alphabetical/chronological order
     *  5. Checks execution log: skips if already migrated, otherwise calls up() and logs
     * 
     * Supported locations:
     *  - Container: App\Containers\{Name}\Migration\
     *  - Ship:      App\Ship\Migration\
     * 
     * Note: Unlike the Seed runner which calls create(), this runner 
     * invokes the up() method on each migration class to apply schema changes.
     * 
     * @see self::checkLog()   to verify if a migration was already executed
     * @see self::writeLog()   to record a successful migration
     * @see self::isTable()    to check tracking table existence
     */
    public function actionIndex(): void
    {
        // Prompt for container name (optional)
        Cli::printer("📦 Enter container (empty for Ship): ", "light_cyan");
        $container = ucfirst(trim(Cli::reader()));

        // Validate container name if provided
        if (!empty($container) && !preg_match('/^[A-Z][a-zA-Z0-9]*$/', $container)) {
            Cli::printer("❌ Invalid container name. Use CamelCase (e.g., User, BlogPost)" . PHP_EOL, "light_red");
            return;
        }

        // Resolve migration directory and namespace
        if (!empty($container)) {
            $migrationPath = Rudra::config()->get('app.path') . "/app/Containers/$container/Migration/";
            $namespace = "App\\Containers\\$container\\Migration\\";
        } else {
            $migrationPath = Rudra::config()->get('app.path') . "/app/Ship/Migration/";
            $namespace = "App\\Ship\\Migration\\";
        }

        // Check if migration directory exists
        if (!is_dir($migrationPath)) {
            Cli::printer("⚠️  Migration directory does not exist: $migrationPath" . PHP_EOL, "light_yellow");
            return;
        }

        // Ensure migration log table exists
        if (!$this->isTable()) {
            $this->up();
        }

        // Get all PHP files in the directory (ignores . and ..)
        $files = array_filter(scandir($migrationPath), function ($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'php';
        });

        if (empty($files)) {
            Cli::printer("ℹ️  No migrations found" . PHP_EOL, "light_cyan");
            return;
        }

        // Process each migration file
        foreach ($files as $filename) {
            $className = strstr($filename, '.', true);
            $migrationName = $namespace . $className;

            // Check if class exists to prevent fatal errors
            if (!class_exists($migrationName)) {
                Cli::printer("❌ Class '$migrationName' not found. Skipping." . PHP_EOL, "light_red");
                continue;
            }

            if ($this->checkLog($migrationName)) {
                Cli::printer("⚠️  $migrationName already migrated. Skipping." . PHP_EOL, "light_yellow");
            } else {
                (new $migrationName)->up();
                $this->writeLog($migrationName);
                Cli::printer("✅ $migrationName migrated successfully" . PHP_EOL, "light_green");
            }
        }
    }
}
