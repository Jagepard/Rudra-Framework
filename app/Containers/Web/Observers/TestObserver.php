<?php

namespace App\Containers\Web\Observers;

use Rudra\EventDispatcher\ObserverInterface;

class TestObserver implements ObserverInterface
{
    public function onEvent($id)
    {
        dump(__CLASS__ . $id);
    }
}