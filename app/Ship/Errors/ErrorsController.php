<?php

namespace App\Ship\Errors;

use App\Ship\ShipController;
use Rudra\View\ViewFacade as View;

class ErrorsController extends ShipController
{
    public function init()
    {
        View::setup(dirname(__DIR__) . '/', "Errors/UI/tmpl");

        data([
            "title" => "Rudra Framework",
        ]);
    }
}
