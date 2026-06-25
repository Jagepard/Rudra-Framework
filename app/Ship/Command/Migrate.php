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
        Cli::printer("Enter container (empty for Ship): ", "magneta");
        $container = ucfirst(str_replace(PHP_EOL, "", Cli::reader()));

        if (!empty($container)) {
            $fileList  = array_slice(scandir(Rudra::config()->get('app.path') . "/app/Containers/" . $container . "/Migration/"), 2);
            $namespace = "App\\Containers\\$container\\Migration\\";
        } else {
            $fileList  = array_slice(scandir(Rudra::config()->get('app.path') . "/app/Ship/Migration/"), 2);
            $namespace = "App\\Ship\\Migration\\";
        }


        if (!$this->isTable()) {
            $this->up();
        }

        foreach ($fileList as $filename) {
            $migrationName = $namespace . strstr($filename, '.', true);

            if ($this->checkLog($migrationName)) {
                Cli::printer("⚠️  $migrationName was migrated" . PHP_EOL, "light_yellow");
            } else {
                (new $migrationName)->up();
                Cli::printer("✅ $migrationName migrated successfully" . PHP_EOL, "light_green");
                $this->writeLog($migrationName);
            }
        }
    }
}
