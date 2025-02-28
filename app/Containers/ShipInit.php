<?php

namespace App\Containers;

use Rudra\Container\Facades\Rudra;
use App\Containers\Web\Observer\TestObserver;
use App\Containers\Web\Listener\MessageListener;
use Rudra\EventDispatcher\EventDispatcherFacade as Dispatcher;

/**
 * the ShipInit trait provides application initialization and event listener registration.
 * ---------------------------------------------------------------------------------------
 * Трейт ShipInit обеспечивает инициализацию приложения и регистрацию слушателей событий.
 */
trait ShipInit
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
