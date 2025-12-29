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
