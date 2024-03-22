<?php

namespace App\Containers\Web;

use App\Ship\ShipController;
use Rudra\Container\Facades\Rudra;
use Rudra\View\ViewFacade as View;
use Rudra\Controller\ContainerControllerInterface;

class WebController extends ShipController implements ContainerControllerInterface
{
    public function containerInit(): void
    {
        $config = require_once "config.php";

        Rudra::binding()->set($config['contracts']);
        Rudra::waiting()->set($config['services']);

        View::setup(dirname(__DIR__) . '/', "Web/UI/tmpl", "Web/UI/cache");

        data([
            "title" => "Rudra Framework:: Web Container",
        ]);
    }
}
