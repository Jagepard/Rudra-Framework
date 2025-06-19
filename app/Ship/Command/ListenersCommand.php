<?php

namespace App\Ship\Command;

use Rudra\EventDispatcher\EventDispatcherFacade as EventDispatcher;

class ListenersCommand
{
    public function actionIndex(): void
    {
        $mask  = "| %-3s | %-15s | %-49s | %-15s |" . PHP_EOL;
        $frame = "\e[1;34m+-----+-----------------+---------------------------------------------------+-----------------+\e[m" . PHP_EOL;

        echo $frame;
        printf("\e[1;95m" . $mask . "\e[m", "#", "Event", "Listener", "Action");
        echo $frame;
        $this->getTable(EventDispatcher::getListeners(), $mask);
        echo $frame;
    }

    protected function getTable(array $data, string $mask): void
    {
        $i = 1;
        $colors = ["\e[0;36m", "\e[0;32m"]; // чередующиеся цвета строк

        foreach ($data as $name => $listeners) {
            $color = $colors[($i - 1) % 2];
            printf(
                $color . $mask . "\e[m",
                $i,
                $name,
                $listeners["listener"],
                $listeners["method"] ?? 'actionIndex'
            );
            $i++;
        }
    }
}
