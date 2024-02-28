<?php

namespace App\Ship\Command;

use Exception;
use Symfony\Component\Yaml\Yaml;
use Rudra\Cli\ConsoleFacade as Cli;

class ArrayToYml
{
    public function actionIndex()
    {
        Cli::printer("Put the file containing the array into a directory\n", "green");
        Cli::printer("Enter filename with php Array: ", "magneta");
        $filename = trim(fgets(fopen("php://stdin", "r")));

        try {
            $array = include("config/$filename.php");
            $yaml = Yaml::dump($array);
            file_put_contents("config/$filename.yml", $yaml);

            Cli::printer("Yml was created" . PHP_EOL, "cyan", );
        } catch (Exception $e) {
            echo 'Exception: ',  $e->getMessage(), "\n";
        }
    }
}
