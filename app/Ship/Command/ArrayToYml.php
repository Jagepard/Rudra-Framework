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
use Rudra\Container\Facades\Rudra;
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
     *  2. Validates that the file exists and returns an array
     *  3. Converts the array to YAML using Yaml::dump()
     *  4. Saves the result as config/{filename}.yml
     * 
     * Example:
     *  - Input:  config/database.php  (returns an array)
     *  - Output: config/database.yml  (YAML representation)
     * 
     * @see Yaml::dump() for the underlying YAML serialization
     */
    public function actionIndex(): void
    {
        // Display header
        echo PHP_EOL;
        Cli::printer("🔄 PHP Array to YAML Converter" . PHP_EOL, "light_magenta");
        echo PHP_EOL;

        // Display instructions
        Cli::printer("ℹ️  Place the PHP file containing the array into the config/ directory" . PHP_EOL, "cyan");

        // Prompt for filename
        Cli::printer("📄 Enter the PHP filename (without .php extension): ", "cyan");
        $filename = trim(Cli::reader());

        // Validate filename is not empty
        if (empty($filename)) {
            Cli::printer("❌ Filename cannot be empty" . PHP_EOL, "light_red");
            return;
        }

        // Validate filename format (alphanumeric, underscores, hyphens only)
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $filename)) {
            Cli::printer("❌ Invalid filename. Use only letters, numbers, underscores, and hyphens" . PHP_EOL, "light_red");
            return;
        }

        // Resolve paths using app.path config
        $configPath = Rudra::config()->get('app.path') . '/config';
        $phpFile = $configPath . "/$filename.php";
        $ymlFile = $configPath . "/$filename.yml";

        // Check if PHP file exists
        if (!file_exists($phpFile)) {
            Cli::printer("❌ File not found: $phpFile" . PHP_EOL, "light_red");
            return;
        }

        try {
            // Include the PHP file and extract the array
            $array = include($phpFile);

            // Validate that the file returned an array
            if (!is_array($array)) {
                Cli::printer("❌ The file must return an array. Got: " . gettype($array) . PHP_EOL, "light_red");
                return;
            }

            // Check if array is empty
            if (empty($array)) {
                Cli::printer("⚠️  The array is empty. YAML file will be created but will be empty" . PHP_EOL, "light_yellow");
            }

            // Convert array to YAML
            $yaml = Yaml::dump($array, 10, 2); // 10 = inline level, 2 = indentation

            // Write YAML file
            if (file_put_contents($ymlFile, $yaml) === false) {
                Cli::printer("❌ Failed to write YAML file: $ymlFile" . PHP_EOL, "light_red");
                return;
            }

            // Success message with file size
            $fileSize = filesize($ymlFile);
            Cli::printer("✅ YAML file created: $ymlFile ($fileSize bytes)" . PHP_EOL, "light_green");

        } catch (Exception $e) {
            Cli::printer("❌ Error: " . $e->getMessage() . PHP_EOL, "light_red");
        }
    }
}
