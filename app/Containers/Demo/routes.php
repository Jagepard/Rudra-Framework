<?php

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

use Rudra\Router\RouterFacade as Router;

if (php_sapi_name() != "cli") {
    Router::get("callable/:name", function ($name) {
            echo "Hello $name!";
        }
    );
}

return [
    \App\Containers\Demo\Controller\IndexController::class,
];
