<?php declare(strict_types=1);

namespace App\Containers\Demo\Observer;

use App\Containers\Demo\Tools\HelperTrait;
use Rudra\EventDispatcher\ObserverInterface;

class TestObserver implements ObserverInterface
{
    use HelperTrait;
    
    public function onEvent($param): void
    {
        $this->info([
            'observer' => __CLASS__,
            'event' => 'one',
            'type' => 'stateless notification',
            'param' => $param,
            'timestamp' => date('H:i:s'),
        ]);
    }
}
