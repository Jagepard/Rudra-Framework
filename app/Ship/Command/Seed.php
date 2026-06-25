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
        Cli::printer("Enter container (empty for Ship): ", "magneta");
        $container = ucfirst(str_replace(PHP_EOL, "", Cli::reader()));

        if (!empty($container)) {
            $fileList  = array_slice(scandir(str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Containers/" . $container . "/Seed/")), 2);
            $namespace = "App\\Containers\\$container\\Seed\\";
        } else {
            $fileList  = array_slice(scandir(str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Ship/Seed/")), 2);
            $namespace = "App\\Ship\\Seed\\";
        }

        if (!$this->isTable()) {
            $this->up();
        }

        foreach ($fileList as $filename) {

            $seedName = $namespace . strstr($filename, '.', true);

            if ($seedName === 'App\Ship\Seed\AbstractSeed') {
                continue;
            }

            if ($this->checkLog($seedName)) {
                Cli::printer("⚠️  $seedName was seeded" . PHP_EOL, "light_yellow");
            } else {
                (new $seedName)->create();
                Cli::printer("✅  $seedName seeded successfully" . PHP_EOL, "light_green");
                $this->writeLog($seedName);
            }
        }
    }
}
