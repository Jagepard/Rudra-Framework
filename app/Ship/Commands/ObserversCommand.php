<?php

namespace App\Ship\Commands;

use Rudra\EventDispatcher\EventDispatcherFacade as EventDispatcher;

class ObserversCommand
{
    public function actionIndex()
    {
        $mask = "|%-5.5s |%-20.20s|%-45.45s|%-20.20s| x |" . PHP_EOL;
        printf("\e[1;35m" . $mask . "\e[m", " ", "event", "observer", "action");

        foreach (EventDispatcher::getObservers() as $event => $observer) {
            $this->getTable($event, $observer);
        }
    }

    protected function getTable($event, array $data)
    {
        $mask = "|%-5.5s |%-20.20s|%-45.45s|%-20.20s| x |" . PHP_EOL;
        $i    = 1;

        foreach ($data as $name => $observer) {
            printf("\e[5;36m" . $mask . "\e[m", $i, $event, $observer["subscriber"], $observer["method"]);
            $i++;
        }
    }
}
