<?php

namespace App\Ship\Commands;

use Rudra\Cli\ConsoleFacade as Cli;

class ServeCommand
{
    public function actionIndex()
    {
        Cli::printer("Rudra is running:", "cyan");
        exec('php -S 127.0.0.1:8000 -t public');
    }
}
