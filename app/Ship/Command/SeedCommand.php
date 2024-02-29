<?php

namespace App\Ship\Command;

use App\Ship\Utils\DatabaseLogger;
use Rudra\Container\Facades\Rudra;
use Rudra\Cli\ConsoleFacade as Cli;

class SeedCommand extends DatabaseLogger
{
    public function __construct()
    {
        $this->table = "rudra_seeds";
        parent::__construct();
    }

    public function actionIndex()
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
                Cli::printer("The $seedName is already seeded" . PHP_EOL, "light_yellow");
            } else {
                (new $seedName)->create();
                Cli::printer("The $seedName was seed" . PHP_EOL, "light_green");
                $this->writeLog($seedName);
            }
        }
    }
}
