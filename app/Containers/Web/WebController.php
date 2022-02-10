<?php

namespace App\Containers\Web;

use App\Ship\ShipController;
use App\Containers\Web\Models\Web;
use Rudra\View\ViewFacade as View;

class WebController extends ShipController
{
    public function init()
    {
        View::setup([
            "base.path"      => dirname(__DIR__) . '/',
            "engine"         => "native",
            "view.path"      => "Web/UI/tmpl",
            "cache.path"     => "Web/UI/cache",
            "file.extension" => "phtml",
        ]);

        data([
            "title" => "Rudra Framework",
        ]);
    }
}
