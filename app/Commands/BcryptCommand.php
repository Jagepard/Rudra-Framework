<?php

namespace App\Commands;

use Rudra\Cli\ConsoleFacade as Cli;
use Rudra\Auth\AuthFacade as Auth;
use Rudra\Container\Facades\Request;

class BcryptCommand
{
    public function actionIndex()
    {
        Request::server()->set([
            "REMOTE_ADDR"     => "127.0.0.1",
            "HTTP_USER_AGENT" => "Mozilla",
        ]);

        Cli::printer("Enter password: ", "cyan");
        $password = trim(fgets(fopen("php://stdin", "r")));

        Cli::printer(Auth::bcrypt($password), "magneta");
    }
}
