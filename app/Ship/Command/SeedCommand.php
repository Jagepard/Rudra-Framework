<?php

namespace App\Ship\Commands;

use Rudra\Cli\ConsoleFacade as Cli;
use Rudra\Container\Facades\Rudra;

class SeedCommand
{
    public function actionIndex()
    {
        Cli::printer("Enter container (empty for Ship): ", "magneta");
        $container = ucfirst(str_replace(PHP_EOL, "", Cli::reader()));

        if (!empty($container)) {
            $fileList  = array_slice(scandir(str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Containers/" . $container . "/Seeds/")), 2);
            $namespace = "App\\Containers\\$container\\Seeds\\";
        } else {
            $fileList  = array_slice(scandir(str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Ship/Seeds/")), 2);
            $namespace = "App\\Ship\\Seeds\\";
        }
 
        $historyPath = str_replace('/', DIRECTORY_SEPARATOR, Rudra::config()->get('app.path') . "/app/Ship/Data/SeedsHistory.php");
        $history     = require_once $historyPath;

        foreach ($fileList as $filename) {

            $seedName = $namespace . strstr($filename, '.', true);

            if ($seedName === 'App\Ship\Seeds\AbstractSeed') {
                continue;
            }

            if (in_array($seedName, $history)) {
                Cli::printer("The $seedName is already seeded" . PHP_EOL, "yellow");
            } else {
                (new $seedName)->create();
                Cli::printer("The $seedName was seed" . PHP_EOL, "light_green");

                if (file_exists($historyPath)) {
                    $contents = file_get_contents($historyPath);
                    $contents = str_replace("];", '', $contents);
                    file_put_contents($historyPath, $contents);
                    $contents = <<<EOT
    "$seedName",
];
EOT;
                    file_put_contents($historyPath, $contents, FILE_APPEND | LOCK_EX);
                }
            }
        }
    }
}
