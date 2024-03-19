<?php

namespace App\Containers\Web;

use App\Ship\ShipController;
use Rudra\View\ViewFacade as View;
use Rudra\Controller\ContainerControllerInterface;

class WebController extends ShipController implements ContainerControllerInterface
{
    public function containerInit(): void
    {
        View::setup(dirname(__DIR__) . '/', "Web/UI/tmpl", "Web/UI/cache");

        data([
            "title" => "Rudra Framework:: Web Container",
        ]);
    }
}
