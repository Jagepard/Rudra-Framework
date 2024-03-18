<?php

namespace App\Ship;

use App\Containers\Web\Listener\MessageListener;
use App\Containers\Web\Observer\TestObserver;
use App\Ship\Utils\HelperTrait;
use Rudra\Controller\Controller;
use Rudra\Container\Facades\Rudra;
use Rudra\EventDispatcher\EventDispatcherFacade as Dispatcher;

class ShipController extends Controller
{
    use HelperTrait;

    public function shipInit()
    {
        if (Rudra::config()->get("environment") === "development") {
            Rudra::get("debugbar")['time']->stopMeasure('routing');
            data([
                "debugbar" => Rudra::get("debugbar")->getJavascriptRenderer(),
            ]);
        }

        $this->eventRegistration();
    }

    public function eventRegistration()
    {
        Dispatcher::addListener('message', [MessageListener::class, 'info']);
        Dispatcher::attachObserver('one', [TestObserver::class, 'onEvent'], __CLASS__);
    }
}
