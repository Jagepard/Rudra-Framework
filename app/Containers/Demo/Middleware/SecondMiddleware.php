<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace App\Containers\Demo\Middleware;

use Rudra\Router\RouterFacade as Router;
use App\Containers\Demo\Tools\HelperTrait;

class SecondMiddleware
{
    use HelperTrait;

    public function __invoke(array $next): void
    {
        $this->info("▶ SecondMiddleware BEFORE");

        if ($next) {
            Router::handleMiddleware($next);
        }

        $this->info("◀ SecondMiddleware AFTER");
    }
}
