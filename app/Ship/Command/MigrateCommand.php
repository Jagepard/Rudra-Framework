<?php

namespace App\Ship\Command;

use App\Ship\Utils\DatabaseLogger;
use Rudra\Container\Facades\Rudra;
use Rudra\Cli\ConsoleFacade as Cli;

class MigrateCommand extends DatabaseLogger
{
    public function __construct()
    {
        $this->table = "rudra_migrations";
        parent::__construct();
    }

    public function actionIndex()
    {
        Cli::printer("Enter container (empty for Ship): ", "magneta");
        $container = ucfirst(str_replace(PHP_EOL, "", Cli::reader()));

        if (!empty($container)) {
            $fileList  = array_slice(scandir(str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Containers/" . $container . "/Migration/")), 2);
            $namespace = "App\\Containers\\$container\\Migration\\";
        } else {
            $fileList  = array_slice(scandir(str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Ship/Migration/")), 2);
            $namespace = "App\\Ship\\Migration\\";
        }

        if (!$this->isTable()) {
            $this->up();
        }

        foreach ($fileList as $filename) {
            $migrationName = $namespace . strstr($filename, '.', true);

            if ($this->checkLog($migrationName)) {
                Cli::printer("The $migrationName is already migrated" . PHP_EOL, "light_yellow");
            } else {
                (new $migrationName)->up();
                Cli::printer("The $migrationName has migrate" . PHP_EOL, "light_green");
                $this->writeLog($migrationName);
            }
        }
    }
}
