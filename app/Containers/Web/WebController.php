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
            "base.path"      => dirname(__DIR__) . DIRECTORY_SEPARATOR,
            "engine"         => "native",
            "view.path"      => 'Web' . DIRECTORY_SEPARATOR . 'UI' . DIRECTORY_SEPARATOR . 'tmpl',
            "cache.path"     => 'Web' . DIRECTORY_SEPARATOR . 'UI' . DIRECTORY_SEPARATOR . 'cache',
            "file.extension" => "phtml",
        ]);

        data([
            "title" => "Rudra Framework",
        ]);
    }
}
