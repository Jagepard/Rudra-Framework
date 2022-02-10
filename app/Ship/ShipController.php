<?php

namespace App\Ship;

use Rudra\Controller\Controller;
use Rudra\Container\Facades\Rudra;

class ShipController extends Controller
{
    public function generalPreCall()
    {
        if (Rudra::config()->get("environment") === "development") {
            Rudra::get("debugbar")['time']->stopMeasure('routing');
            data([
                "debugbar" => Rudra::get("debugbar")->getJavascriptRenderer(),
            ]);
        }
    }

    public function eventRegistration()
    {

    }

    public function info(string $message): void
    {
        Rudra::get("debugbar")['messages']->info($message);
    }
}
