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

class Serve
{
    public function actionIndex(): void
    {
        Cli::printer("🌐 Rudra is running:", "cyan");
        exec('php -S 127.0.0.1:8000 -t public');
    }
}
