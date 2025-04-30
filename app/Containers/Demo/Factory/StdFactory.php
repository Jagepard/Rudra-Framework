<?php

namespace App\Containers\Demo\Factory;

use stdClass;

class StdFactory
{
    public function create()
    {
        $std = new stdClass;
        $std->method = __METHOD__ . '::Created by Factory';

        return $std;
    }
}
