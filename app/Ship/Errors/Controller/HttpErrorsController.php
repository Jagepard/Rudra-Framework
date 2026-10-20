<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace App\Ship\Errors\Controller;

use Rudra\Exceptions\RouterException;
use App\Ship\Errors\ErrorsController;

class HttpErrorsController extends ErrorsController
{
    public function error404(): void
    {
        data(['content' => view('errors.404')]);
        render('layout', data());
    }

    public function error503(): void
    {
        data(['content' => view('errors.503')]);
        render('layout', data());
    }
}
