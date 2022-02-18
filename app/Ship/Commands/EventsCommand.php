<?php

namespace App\Ship\Commands;

use Rudra\EventDispatcher\EventDispatcherFacade as EventDispatcher;

class EventsCommand
{
    public function actionIndex()
    {
        $mask = "|%-5.5s |%-20.20s|%-35.35s|%-20.20s| x |" . PHP_EOL;
        printf("\e[1;35m" . $mask . "\e[m", " ", "event", "listener", "action");
        $this->getTable(EventDispatcher::getListeners());
    }

    protected function getTable(array $data)
    {
        $mask = "|%-5.5s |%-20.20s|%-35.35s|%-20.20s| x |" . PHP_EOL;
        $i    = 1;

        foreach ($data as $name => $routes) {
            printf("\e[5;36m" . $mask . "\e[m", $i, $name, $routes["listener"], $routes["method"]);
            $i++;
        }
    }
}
