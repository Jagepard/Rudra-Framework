<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace App\Containers\Demo\Tools;

use Rudra\Container\Facades\Rudra;

trait HelperTrait
{
    /**
     * Output message to debugbar.
     * Accepts string or array (arrays are formatted as pretty JSON).
     *
     * @param array|string $message Message to display
     */
    public function info(array|string $message): void
    {
        if (is_array($message)) {
            $message = json_encode($message, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        
        Rudra::get("debugbar")['messages']->info($message);
    }
}
