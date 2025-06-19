<?php

namespace App\Ship\Command;

use Rudra\Cli\ConsoleFacade as Cli;

class ConsoleCommand
{
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
