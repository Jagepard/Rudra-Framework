<?php

namespace App\Ship\Errors;

use App\Ship\ShipController;
use Rudra\View\ViewFacade as View;

class ErrorsController extends ShipController
{
    public function containerInit(): void
    {
        View::setup(dirname(__DIR__) . '/', "Errors/UI/tmpl", "Errors/UI/cache");

        data([
            "title" => "Rudra Framework",
        ]);
    }
}
