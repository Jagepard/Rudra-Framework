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

    public string $thema = 
        // "Brite"
        // "Cerulean"
        // "Cosmo"
        // "Cyborg"
        // "Darkly"
        // "Flatly"
        // "Journal"
        // "Litera"
        "Lumen"
        // "Lux"
        // "Materia"
        // "Minty"
        // "Morph"
        // "Pulse"
        // "Quartz"
        // "Sandstone"
        // "Simplex"
        // "Sketchy"
        // "Slate"
        // "Solar"
        // "Spacelab"
        // "Superhero"
        // "United"
        // "Vapor"
        // "Yeti"
        // "Zephyr"
    ;
    
    public function containerInit(): void
    {
        $config = require_once "config.php";
        
        Rudra::binding()->set($config['contracts']);
        Rudra::waiting()->set($config['services']);

        View::setup(dirname(__DIR__) . "/Demo/UI/tmpl", "Demo_");

        data([
            "title" => "Rudra Framework:: Demo Container",
            "thema" => $this->thema,
        ]);
    }
}
