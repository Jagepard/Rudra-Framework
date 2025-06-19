<?php

namespace App\Ship\Utils;

use Rudra\Cli\ConsoleFacade as Cli;

class FileCreator
{
    /**
     * Writes data to a file
     * ---------------------
     * Записывает данные в файл
     *
     * @param array $path
     * @param string $data
     * @return void
     */
    protected function writeFile(array $path, string $data): void
    {
        if (!is_dir($path[0])) {
            mkdir($path[0], 0755, true);
        }

        $fullPath = $path[0] . $path[1];

        if (!file_exists($fullPath)) {
            Cli::printer("✅ File $fullPath was created" . PHP_EOL, "light_green");
            file_put_contents($fullPath, $data);
        } else {
            Cli::printer("⚠️  File $fullPath already exists" . PHP_EOL, "light_yellow");
        }
    }
}
