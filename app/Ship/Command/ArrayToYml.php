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

use Exception;
use Symfony\Component\Yaml\Yaml;
use Rudra\Cli\ConsoleFacade as Cli;

class ArrayToYml
{
    /**
     * 🔄 PHP Array to YAML Converter
     * 
     * CLI utility that converts a PHP array file into YAML format.
     * Useful for creating human-readable configuration files, localization data,
     * or any structured data that benefits from YAML syntax.
     * 
     * Prerequisites:
     *  - Place the source PHP file in the config/ directory
     *  - The file must return an array (e.g. `return ['key' => 'value'];`)
     * 
     * Workflow:
     *  1. Prompts for the PHP filename (without extension)
     *  2. Includes the file to extract the array
     *  3. Converts the array to YAML using Yaml::dump()
     *  4. Saves the result as config/{filename}.yml
     * 
     * Example:
     *  - Input:  config/database.php  (returns an array)
     *  - Output: config/database.yml  (YAML representation)
     * 
     * Note: If the source file does not return an array or contains errors,
     * the conversion will fail with an exception message.
     * 
     * @see Yaml::dump() for the underlying YAML serialization
     */
    public function actionIndex(): void
    {
        Cli::printer("Put the file containing the array into the config directory" . PHP_EOL, "green");
        Cli::printer("Enter the name of the php file containing the array: ", "magneta");
        $filename = trim(fgets(fopen("php://stdin", "r")));

        try {
            $array = include("config/$filename.php");
            $yaml = Yaml::dump($array);
            file_put_contents("config/$filename.yml", $yaml);

            Cli::printer("✅ Yml was created" . PHP_EOL, "cyan", );
        } catch (Exception $e) {
            echo 'Exception: ',  $e->getMessage(), PHP_EOL;
        }
    }
}
