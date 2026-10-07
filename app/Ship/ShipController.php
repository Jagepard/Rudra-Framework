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

use App\Ship\Utils\Theme;
use DebugBar\DebugBarException;
use Rudra\Controller\Controller;
use Rudra\Container\Facades\Rudra;
use Rudra\Container\Facades\Request;
use Rudra\Container\Facades\Session;
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
            $debugBar = Rudra::get("debugbar");
            try {
                $debugBar['time']->stopMeasure('routing');
                $debugBar['time']->stopMeasure('application');
            } catch (DebugBarException $e) {
            }

            data([
                "debugbar" => $debugBar->getJavascriptRenderer(),
            ]);
        }

        $isAuth = Session::has('user');

        data([
            "theme"   => Theme::Yeti,
            "baseUrl" => Rudra::config()->get('url'),
            "environment" => Rudra::config()->get('environment'),
            "currentPage" => Request::server()->get('REQUEST_URI') ?? '/',
            "isAuth" => $isAuth,
            "user"   => $isAuth ? Session::get("user") : null,
            "csrf"   => Session::get('csrf_token')[0] ?? ''
        ]);

        $this->eventRegistration();
    }

    #[\Override]
    public function eventRegistration(): void
    {
        Dispatcher::addListener('message', [MessageListener::class, 'info']);
        Dispatcher::attachObserver('one', [TestObserver::class, 'onEvent'], __CLASS__);
    }
}
