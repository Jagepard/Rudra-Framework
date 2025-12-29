<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace App\Ship\Command;

use Rudra\Cli\ConsoleFacade as Cli;
use Rudra\Auth\AuthFacade as Auth;
use Rudra\Container\Facades\Request;

class Bcrypt
{
    public function actionIndex(): void
    {
        Request::server()->set([
            "REMOTE_ADDR"     => "127.0.0.1",
            "HTTP_USER_AGENT" => "Mozilla",
        ]);

        Cli::printer("Enter password: ", "magneta");
        $password = trim(fgets(fopen("php://stdin", "r")));

        Cli::printer(Auth::bcrypt($password) . PHP_EOL, "light_green", );
    }
}
