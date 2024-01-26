<?php

namespace App\Ship\Commands;

use Rudra\Cli\ConsoleFacade as Cli;
use Rudra\Container\Facades\Rudra;

class MigrateCommand
{
    public function actionIndex()
    {
        Cli::printer("Enter container (empty for Ship): ", "magneta");
        $container = ucfirst(str_replace(PHP_EOL, "", Cli::reader()));

        if (!empty($container)) {
            $fileList  = array_slice(scandir(str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Containers/" . $container . "/Migrations/")), 2);
            $namespace = "App\\Containers\\$container\\Migrations\\";
        } else {
            $fileList  = array_slice(scandir(str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Ship/Migrations/")), 2);
            $namespace = "App\\Ship\\Migrations\\";
        }

        $historyPath = str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Ship/Data/MigrationsHistory.php");
        $history     = require_once $historyPath;

        foreach ($fileList as $filename) {
            $migrationName = $namespace . strstr($filename, '.', true);

            if (in_array($migrationName, $history)) {
                Cli::printer("The $migrationName is already migrated" . PHP_EOL, "light_yellow");
            } else {
                (new $migrationName)->up();
                Cli::printer("The $migrationName has migrate" . PHP_EOL, "light_green");

                if (file_exists($historyPath)) {
                    $contents = file_get_contents($historyPath);
                    $contents = str_replace("];", '', $contents);
                    file_put_contents($historyPath, $contents);
                    $contents = <<<EOT
    "$migrationName",
];
EOT;
                    file_put_contents($historyPath, $contents, FILE_APPEND | LOCK_EX);
                }
            }
        }
    }
}
