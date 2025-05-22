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

    protected array $cache_time;

    public function containerInit(): void
    {
        $config = require_once "config.php";
        $cache_time = config('cache.time');
        $this->cache_time = $cache_time;
        
        Rudra::binding()->set($config['contracts']);
        Rudra::waiting()->set($config['services']);

        View::setup(dirname(__DIR__) . "/Demo/UI/tmpl", "Demo_");

        data([
            "title" => "Rudra Framework:: Demo Container",
        ]);
    }
}
