<?php

namespace App\Ship\Command;

use Rudra\Cli\ConsoleFacade as Cli;

class ConsoleCommand
{
    public function actionIndex(): void
    {
        $mask = "|%-5.5s |%-20.20s|%-45.45s|%-20.20s| x |" . PHP_EOL;
        printf("\e[5;35m" . $mask . "\e[m", " ", "command", "controller", "action");
        $this->getTable(Cli::getRegistry());
    }

    protected function getTable(array $data): void
    {
        $mask = "|%-5.5s |%-20.20s|%-45.45s|%-20.20s| x |" . PHP_EOL;
        $i    = 1;

        foreach ($data as $name => $routes) {
            printf("\e[5;36m" . $mask . "\e[0m", $i, $name, $routes[0], $routes[1] ?? "actionIndex");
            $i++;
        }
    }
}
