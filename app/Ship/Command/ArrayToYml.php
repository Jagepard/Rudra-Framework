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
