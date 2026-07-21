<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

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
        // "Brite" //
        // "Cerulean" //
        // "Cosmo" //
        // "Cyborg" //
        // "Darkly" //
        // "Flatly" //
        // "Journal" //
        // "Litera" //
        // "Lumen" //++++
        // "Lux" //
        // "Materia" //
        // "Minty" //
        // "Morph" //
        // "Pulse" //
        // "Quartz" //
        // "Sandstone" //
        // "Simplex" //
        // "Sketchy" //++++
        // "Slate" //
        // "Solar" //
        // "Spacelab" //
        // "Superhero" //
        // "United" //
        // "Vapor" //
        "Yeti" //++++
        // "Zephyr" //
    ;
    
    #[\Override]
    public function containerInit(): void
    {
        $config = require_once "config.php";
        
        Rudra::config()->set($config);
        Rudra::binding()->set($config['contracts']);
        Rudra::waiting()->set($config['services']);

        View::setup(dirname(__DIR__) . "/Demo/UI/tmpl", "Demo_");

        data([
            "title" => "Rudra Framework:: Demo Container",
            "thema" => $this->thema,
        ]);
    }
}
