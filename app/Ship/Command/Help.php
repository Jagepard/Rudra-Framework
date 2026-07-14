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

class Help
{
    private const MASK  = "| %-2s | %-22s | %-41s | %-15s |" . PHP_EOL;
    private const FRAME = "+----+------------------------+-------------------------------------------+-----------------+" . PHP_EOL;

    /**
     * 📖 CLI Command Reference
     * 
     * Displays a complete list of all registered CLI commands in Rudra Framework.
     * This is the entry point for discovering available commands and their locations.
     * 
     * How it works:
     * 1. Fetches the command registry from the CLI facade.
     * 2. Renders a formatted, colorized ASCII table with all available commands.
     * 
     * Table columns:
     *  - #          : Command index
     *  - Command    : The CLI command name (as typed in terminal)
     *  - Controller : Fully qualified class name handling the command
     *  - Action     : The method invoked (e.g. actionIndex)
     * 
     * Usage:
     *  - Run this command to see all available CLI utilities
     *  - Use the listed command names to invoke specific tools
     *  - Refer to each command's PHPDoc for detailed usage instructions
     * 
     * @see Cli::getRegistry() for the command registry source
     * @see self::renderTable() for table row rendering
     */
    public function actionIndex(): void
    {
        echo PHP_EOL;
        Cli::printer("📖 Rudra CLI Command Reference:" . PHP_EOL, "light_magenta");

        // Top frame - use Cli::printer instead of echo
        Cli::printer(self::FRAME, "light_blue");

        // Table header - use Cli::printer with background
        Cli::printer(sprintf(self::MASK, "#", "Command", "Controller", "Action"), "light_cyan");

        // Separator frame
        Cli::printer(self::FRAME, "light_blue");

        // Data rows
        $this->renderTable(Cli::getRegistry());

        // Bottom frame  
        Cli::printer(self::FRAME, "light_blue");
    }

    /**
     * Renders all registered CLI commands as colorized table rows.
     * Uses alternating colors (light_blue/light_green) for better readability.
     */
    protected function renderTable(array $data): void
    {
        $i = 1;
        $colors = ["light_blue", "light_green"]; // alternating row colors

        foreach ($data as $name => $routes) {
            $color = $colors[($i - 1) % 2];
            $row = sprintf(self::MASK, $i, $name, $routes[0], $routes[1] ?? "actionIndex");
            Cli::printer($row, $color);
            $i++;
        }
    }
}
