<?php

namespace App\Ship\Command;

use Rudra\Cli\ConsoleFacade as Cli;

class ServeCommand
{
    public function actionIndex(): void
    {
        Cli::printer("Rudra is running:", "cyan");
        exec('php -S 127.0.0.1:8000 -t public');
    }
}
