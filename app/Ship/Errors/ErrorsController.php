<?php

namespace App\Ship\Errors;

use App\Ship\ShipController;
use Rudra\View\ViewFacade as View;

class ErrorsController extends ShipController
{
    public function init()
    {
        View::setup([
            "base.path"      => dirname(__DIR__) . '/',
            "engine"         => "native",
            "view.path"      => "Errors/UI/tmpl",
            "file.extension" => "phtml",
        ]);

        data([
            "title" => "Rudra Framework",
        ]);
    }
}
