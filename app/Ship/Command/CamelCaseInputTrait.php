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
     * Prompts the user for a name in any format and converts it to PascalCase.
     * Accepts: item_tag_map, itemTagMap, ItemTagMap, item-tag-map.
     */
    protected function getValidCamelCaseName(string $prompt, string $entityLabel): string
    {
        $name = '';
        while (empty($name)) {
            Cli::printer($prompt, "light_cyan");
            $raw = trim(Cli::reader());

            if (empty($raw)) {
                Cli::printer("⚠️  $entityLabel name cannot be empty" . PHP_EOL, "light_yellow");
                continue;
            }

            $name = $this->toPascalCase($raw);

            // Safety check: result must be a valid PHP class name
            if (!preg_match('/^[A-Z][a-zA-Z0-9]*$/', $name)) {
                Cli::printer("❌ Invalid $entityLabel name after conversion: '$name'" . PHP_EOL, "light_red");
                $name = '';
            }
        }

        return $name;
    }

    /**
     * Prompts the user for a valid snake_case table name.
     */
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

    /**
     * Convert any naming convention to PascalCase.
     *
     * item_tag_map → ItemTagMap
     * itemTagMap   → ItemTagMap
     * item-tag-map → ItemTagMap
     * ItemTagMap   → ItemTagMap
     */
    protected function toPascalCase(string $input): string
    {
        // Split by underscores, hyphens, or spaces
        $words = preg_split('/[\s_\-]+/', $input, -1, PREG_SPLIT_NO_EMPTY);

        // If no delimiters found, split by uppercase boundaries (camelCase input)
        if (count($words) === 1) {
            $words = preg_split('/(?=[A-Z])/', $input, -1, PREG_SPLIT_NO_EMPTY);
        }

        return implode('', array_map(
            static fn(string $w): string => ucfirst(strtolower($w)),
            $words
        ));
    }

    /**
     * Convert PascalCase to snake_case.
     *
     * ItemTagMap → item_tag_map
     * BlogPost   → blog_post
     */
    protected function toSnakeCase(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }
}
