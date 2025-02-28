<?php

namespace App\Ship;

use App\Containers\ShipInit;
use Rudra\Controller\Controller;
use Rudra\Controller\ShipControllerInterface;

class ShipController extends Controller implements ShipControllerInterface
{
    use ShipInit;
}
