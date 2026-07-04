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

use App\Ship\Seed\AbstractSeed;
use Rudra\Container\Facades\Rudra;
use Rudra\Cli\ConsoleFacade as Cli;
use App\Ship\Utils\Database\LoggerAdapter;

class Seed extends LoggerAdapter
{
    public function __construct()
    {
        $this->table = "rudra_seeds";
        parent::__construct();
    }

    /**
     * 🌾 Interactive Seeder Runner
     * 
     * Executes all seeders for a given container (or Ship) and tracks their state.
     * 
     * Workflow:
     *  1. Enter container name → empty input defaults to Ship level
     *  2. Scans the corresponding Seed/ directory for seeder files
     *  3. Initializes the `rudra_seeds` tracking table if it doesn't exist
     *  4. Iterates through seeders, skipping AbstractSeed
     *  5. Checks execution log: skips if already seeded, otherwise runs and logs
     * 
     * Supported locations:
     *  - Container: App\Containers\{Name}\Seed\
     *  - Ship:      App\Ship\Seed\
     * 
     * @see self::checkLog()   to verify if a seeder was already executed
     * @see self::writeLog()   to record a successful seeding
     * @see self::isTable()    to check tracking table existence
     */
    public function actionIndex(): void
    {
        // Prompt for container name (optional)
        Cli::printer("📦 Enter container (empty for Ship): ", "cyan");
        $container = ucfirst(trim(Cli::reader()));

        // Validate container name if provided
        if (!empty($container) && !preg_match('/^[A-Z][a-zA-Z0-9]*$/', $container)) {
            Cli::printer("❌ Invalid container name. Use CamelCase (e.g., User, BlogPost)" . PHP_EOL, "light_red");
            return;
        }

        // Resolve seed directory and namespace
        if (!empty($container)) {
            $seedPath = Rudra::config()->get('app.path') . "/app/Containers/$container/Seed/";
            $namespace = "App\\Containers\\$container\\Seed\\";
        } else {
            $seedPath = Rudra::config()->get('app.path') . "/app/Ship/Seed/";
            $namespace = "App\\Ship\\Seed\\";
        }

        // Check if seed directory exists
        if (!is_dir($seedPath)) {
            Cli::printer("⚠️  Seed directory does not exist: $seedPath" . PHP_EOL, "light_yellow");
            return;
        }

        // Ensure seed log table exists
        if (!$this->isTable()) {
            $this->up();
        }

        // Get all PHP files in the directory
        $files = array_filter(scandir($seedPath), function ($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'php';
        });

        if (empty($files)) {
            Cli::printer("ℹ️  No seeds found" . PHP_EOL, "cyan");
            return;
        }

        // Process each seed file
        foreach ($files as $filename) {
            $className = strstr($filename, '.', true);
            $seedName = $namespace . $className;

            // Skip abstract base class
            if ($seedName === AbstractSeed::class) {
                continue;
            }

            // Check if class exists to prevent fatal errors
            if (!class_exists($seedName)) {
                Cli::printer("❌ Class '$seedName' not found. Skipping." . PHP_EOL, "light_red");
                continue;
            }

            if ($this->checkLog($seedName)) {
                Cli::printer("⚠️  $seedName already seeded. Skipping." . PHP_EOL, "light_yellow");
            } else {
                (new $seedName)->create();
                $this->writeLog($seedName);
                Cli::printer("✅ $seedName seeded successfully" . PHP_EOL, "light_green");
            }
        }
    }
}
