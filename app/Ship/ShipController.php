<?php

namespace App\Ship;

use App\Ship\Utils\HelperTrait;
use Rudra\Controller\Controller;
use Rudra\Container\Facades\Rudra;
use App\Containers\Web\Observer\TestObserver;
use Rudra\Controller\ShipControllerInterface;
use App\Containers\Web\Listener\MessageListener;
use Rudra\EventDispatcher\EventDispatcherFacade as Dispatcher;

class ShipController extends Controller implements ShipControllerInterface
{
    use HelperTrait;

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
