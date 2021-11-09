<?php

namespace App\Commands;

use Rudra\Cli\ConsoleFacade as Cli;

class ConsoleCommand
{
    public function actionIndex()
    {
        $mask = "|%-5.5s |%-20.20s|%-35.35s|%-20.20s| x |\n";
        printf("\e[1;36m" . $mask . "\e[m", " ", "command", "controller", "action");
        $this->getTable(Cli::getRegistry());
    }

    protected function getTable(array $data)
    {
        $mask = "|%-5.5s |%-20.20s|%-35.35s|%-20.20s| x |\n";
        $i    = 1;

        foreach ($data as $name => $routes) {
            printf("\e[1;35m" . $mask . "\e[m", $i, $name, $routes[0], $routes[1] ?? "actionIndex");
            $i++;
        }
    }
}
