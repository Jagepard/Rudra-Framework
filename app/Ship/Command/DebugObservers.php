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

use Rudra\EventDispatcher\EventDispatcherFacade as EventDispatcher;

class DebugObservers
{
    /**
     * 🔭 Debug Observers List
     * 
     * CLI command that displays all registered Observers and the events they listen to.
     * Useful for debugging and verifying the Event Dispatcher configuration.
     * 
     * How it works:
     * 1. Fetches all registered observers from the EventDispatcher.
     * 2. Renders a formatted, colorized ASCII table.
     * 
     * Table columns:
     *  - #        : Observer index
     *  - Event    : The event name/identifier being observed
     *  - Observer : Fully qualified class name of the Observer
     *  - Method   : The method triggered when the event is fired (e.g. onEvent)
     * 
     * @see EventDispatcher::getObservers() for data source
     * @see self::getTable()                for table row rendering
     */
    public function actionIndex(): void
    {
        $mask  = "| %-3s | %-15s | %-49s | %-15s |" . PHP_EOL;
        $frame = "\e[1;34m+-----+-----------------+---------------------------------------------------+-----------------+\e[m" . PHP_EOL;

        echo $frame;
        printf("\e[1;95m" . $mask . "\e[m", "#", "Event", "Observer", "Method");
        echo $frame;
        $this->getTable(EventDispatcher::getObservers(), $mask);
        echo $frame;
    }

    protected function getTable(array $data, string $mask): void
    {
        $i = 1;
        $colors = ["\e[0;36m", "\e[0;32m"];

        foreach ($data as $event => $observers) {
            foreach ($observers as $observer) {
                $color = $colors[($i - 1) % 2];
                printf($color . $mask . "\e[m", $i,
                    $event,
                    $observer['class'],
                    $observer['method']
                );
                $i++;
            }
        }
    }
}
