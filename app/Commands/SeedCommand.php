<?php

namespace App\Commands;

use Rudra\Cli\ConsoleFacade as Cli;
use Rudra\Container\Facades\Rudra;

class SeedCommand
{
    public function actionIndex()
    {
        $fileList    = array_slice(scandir(Rudra::config()->get('app.path') . "/db/Seeds/"), 2);
        $historyPath = Rudra::config()->get('app.path') . "/db/history.php";
        $history     = require_once $historyPath;

        foreach ($fileList as $filename) {
            $seedName = "Db\\Seeds\\" . strstr($filename, '.', true);

            if (in_array($seedName, $history)) {
                Cli::printer("The $seedName is already seeded", "yellow");
            } else {
                (new $seedName)->create();
                Cli::printer("The $seedName was seed", "blue");

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
