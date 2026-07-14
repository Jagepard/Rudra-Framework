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

trait CamelCaseInputTrait
{
    /**
     * Prompts the user for a valid CamelCase name.
     */
    protected function getValidCamelCaseName(string $prompt, string $entityLabel): string
    {
        $name = '';
        while (empty($name)) {
            Cli::printer($prompt, "light_cyan");
            $name = ucfirst(trim(Cli::reader()));
            
            if (empty($name)) {
                Cli::printer("⚠️  $entityLabel name cannot be empty" . PHP_EOL, "light_yellow");
                continue;
            }
            
            if (!preg_match('/^[A-Z][a-zA-Z0-9]*$/', $name)) {
                Cli::printer("❌ Invalid $entityLabel name. Use CamelCase (e.g., User, BlogPost)" . PHP_EOL, "light_red");
                $name = ''; // Reset for the next request
            }
        }

        return $name;
    }

    protected function getValidTableName(string $prompt): string
    {
        $name = '';
        while (empty($name)) {
            Cli::printer($prompt, "cyan");
            $name = trim(Cli::reader());
            
            if (empty($name)) {
                Cli::printer("⚠️  Table name cannot be empty" . PHP_EOL, "light_yellow");
                continue;
            }
            
            if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $name)) {
                Cli::printer("❌ Invalid table name. Use alphanumeric or snake_case (e.g., users, blog_posts)" . PHP_EOL, "light_red");
                $name = '';
            }
        }
        
        return $name;
    }
}
