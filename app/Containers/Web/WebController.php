<?php

namespace App\Containers\Web;

use App\Ship\ShipController;
use App\Containers\Web\Models\Web;
use Rudra\View\ViewFacade as View;

class WebController extends ShipController
{
    public function containerInit()
    {
        View::setup(dirname(__DIR__) . '/', "Web/UI/tmpl", "Web/UI/cache");

        data([
            "title" => "Rudra Framework",
        ]);
    }
}
