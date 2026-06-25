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
     * @see self::getTable()   for table row rendering
     */
    public function actionIndex(): void
    {
        $mask  = "| %-2s | %-22s | %-41s | %-15s |" . PHP_EOL;
        $frame = "\e[1;34m+----+------------------------+-------------------------------------------+-----------------+\e[m" . PHP_EOL;

        echo $frame;
        printf("\e[1;95m" . $mask . "\e[m", "#", "Command", "Controller", "Action");
        echo $frame;
        $this->getTable(Cli::getRegistry(), $mask);
        echo $frame;
    }

    protected function getTable(array $data, string $mask): void
    {
        $i = 1;
        $colors = ["\e[0;36m", "\e[0;32m"]; // чередующиеся цвета строк

        foreach ($data as $name => $routes) {
            $color = $colors[($i - 1) % 2];
            printf($color . $mask . "\e[m", $i, $name, $routes[0], $routes[1] ?? "actionIndex");
            $i++;
        }
    }
}
