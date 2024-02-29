<?php

namespace App\Containers\Web\Observer;

use Rudra\EventDispatcher\ObserverInterface;

class TestObserver implements ObserverInterface
{
    public function onEvent($id)
    {
        dump(__CLASS__ . $id);
    }
}