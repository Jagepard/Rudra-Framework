<?php

namespace App\Ship;

use Rudra\Controller\Controller;
use Rudra\Container\Facades\Rudra;
use App\Containers\Demo\Observer\TestObserver;
use Rudra\Controller\ShipControllerInterface;
use App\Containers\Demo\Listener\MessageListener;
use Rudra\EventDispatcher\EventDispatcherFacade as Dispatcher;

/**
 * In summary, this code is responsible for initializing a ship and registering events.
 * ------------------------------------------------------------------------------------
 * В целом, этот код предназначен для инициализации корабля и регистрации событий.
 */
class ShipController extends Controller implements ShipControllerInterface
{
    public function shipInit(): void
    {
        if (Rudra::config()->get("environment") === "development") {
            
            Rudra::get("debugbar")['time']->stopMeasure('routing');
            Rudra::get("debugbar")['time']->stopMeasure('application');

            data([
                "debugbar" => Rudra::get("debugbar")->getJavascriptRenderer(),
            ]);
        }

        $this->eventRegistration();
    }

    public function eventRegistration(): void
    {
        Dispatcher::addListener('message', [MessageListener::class, 'info']);
        Dispatcher::attachObserver('one', [TestObserver::class, 'onEvent'], __CLASS__);
    }
}
