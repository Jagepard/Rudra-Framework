<?php

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
