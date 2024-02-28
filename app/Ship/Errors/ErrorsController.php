<?php

namespace App\Ship\Errors;

use App\Ship\ShipController;
use Rudra\View\ViewFacade as View;

class ErrorsController extends ShipController
{
    public function containerInit()
    {
        View::setup(dirname(__DIR__) . '/', "Errors/View/tmpl");

        data([
            "title" => "Rudra Framework",
        ]);
    }
}
