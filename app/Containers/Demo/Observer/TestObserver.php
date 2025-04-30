<?php

namespace App\Containers\Demo\Observer;

use Rudra\EventDispatcher\ObserverInterface;

class TestObserver implements ObserverInterface
{
    public function onEvent($param): void
    {
        dump(__CLASS__ . ' ' . $param);
    }
}
