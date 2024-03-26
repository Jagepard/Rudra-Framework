<?php

namespace App\Containers\Web\Factory;

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
