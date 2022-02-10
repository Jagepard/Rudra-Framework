<?php

namespace App\Ship\Commands;

use Rudra\Cli\ConsoleFacade as Cli;
use Rudra\Container\Facades\Rudra;

class MigrateCommand
{
    public function actionIndex()
    {
        $fileList    = array_slice(scandir(Rudra::config()->get('app.path') . "/db/Migrations/"), 2);
        $historyPath = Rudra::config()->get('app.path') . "/db/history.php";
        $history     = require_once $historyPath;

        foreach ($fileList as $filename) {
            $migrationName = "Db\\Migrations\\" . strstr($filename, '.', true);

            if (in_array($migrationName, $history)) {
                Cli::printer("The $migrationName is already migrated\n", "yellow");
            } else {
                (new $migrationName)->up();
                Cli::printer("The $migrationName has migrate\n", "blue");

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
