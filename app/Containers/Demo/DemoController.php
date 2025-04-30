<?php

namespace App\Containers\Demo;

use App\Ship\ShipController;
use Rudra\Container\Facades\Rudra;
use Rudra\View\ViewFacade as View;
use App\Containers\Demo\Tools\HelperTrait;
use Rudra\Controller\ContainerControllerInterface;

class DemoController extends ShipController implements ContainerControllerInterface
{
    use HelperTrait;

    public function containerInit(): void
    {
        $config = require_once "config.php";

        Rudra::binding()->set($config['contracts']);
        Rudra::waiting()->set($config['services']);

        View::setup(dirname(__DIR__) . '/', "Demo/UI/tmpl", "Demo/UI/cache");

        data([
            "title" => "Rudra Framework:: Demo Container",
        ]);
    }
}
