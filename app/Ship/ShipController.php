<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace App\Ship;

use Rudra\Controller\Controller;
use Rudra\Container\Facades\Rudra;
use App\Containers\Demo\Observer\TestObserver;
use Rudra\Controller\ShipControllerInterface;
use App\Containers\Demo\Listener\MessageListener;
use Rudra\EventDispatcher\EventDispatcherFacade as Dispatcher;

/**
 * In summary, this code is responsible for initializing a ship and registering events.
 */
class ShipController extends Controller implements ShipControllerInterface
{
    #[\Override]
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

    #[\Override]
    public function eventRegistration(): void
    {
        Dispatcher::addListener('message', [MessageListener::class, 'info']);
        Dispatcher::attachObserver('one', [TestObserver::class, 'onEvent'], __CLASS__);
    }
}
