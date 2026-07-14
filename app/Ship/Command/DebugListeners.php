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
use Rudra\Cli\ConsoleFacade as Cli;

class DebugListeners
{
    // Table formatting constants
    private const MASK  = "| %-3s | %-15s | %-49s | %-15s |" . PHP_EOL;
    private const FRAME = "+-----+-----------------+---------------------------------------------------+-----------------+" . PHP_EOL;

    /**
     * 🎧 Debug Listeners List
     * 
     * CLI command that displays all registered Listeners and the events they react to.
     * Useful for debugging and verifying the Event Dispatcher configuration.
     * 
     * How it works:
     * 1. Fetches all registered listeners from the EventDispatcher.
     * 2. Renders a formatted, colorized ASCII table.
     * 
     * Table columns:
     *  - #        : Listener index
     *  - Event    : The event name/identifier being listened to
     *  - Listener : Fully qualified class name of the Listener
     *  - Action   : The action method triggered when the event is fired (e.g. handle)
     * 
     * @see EventDispatcher::getListeners() for data source
     * @see self::renderTable()             for table row rendering
     */
    public function actionIndex(): void
    {
        $data = EventDispatcher::getListeners();

        // Display header with emoji
        echo PHP_EOL;
        Cli::printer("🎧 Event Listeners Reference:" . PHP_EOL, "light_magenta");

        // Top frame
        Cli::printer(self::FRAME, "light_blue");

        // Table header with background
        Cli::printer(sprintf(self::MASK, "#", "Event", "Listener", "Action"), "light_cyan");

        // Separator frame
        Cli::printer(self::FRAME, "light_blue");

        // Data rows or empty message
        if (empty($data)) {
            Cli::printer("ℹ️  No listeners registered" . PHP_EOL, "light_cyan");
        } else {
            $this->renderTable($data);
        }

        // Bottom frame
        Cli::printer(self::FRAME, "light_blue");
    }

    /**
     * Renders listener data as colorized table rows.
     * Uses alternating colors (cyan/green) for better readability.
     */
    protected function renderTable(array $data): void
    {
        $i = 1;
        $colors = ["light_blue", "light_green"]; // alternating row colors

        foreach ($data as $name => $listeners) {
            $color = $colors[($i - 1) % 2];
            $row = sprintf(
                self::MASK,
                $i,
                $name,
                $listeners["listener"],
                $listeners["method"] ?? 'actionIndex'
            );
            Cli::printer($row, $color);
            $i++;
        }
    }
}
