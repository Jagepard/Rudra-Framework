<?php

namespace App\Containers\Web\Controllers;

use App\Containers\Web\WebController;

class MainController extends WebController
{
    /**
     * @Routing(url = '')
     */
    public function mainpage()
    {
        data([
            "content" => cache(['mainpage', 'now']) ?? view(["index", 'mainpage']),
       ]);

       $this->info("It's working");

       render("layout", data());
    }
}
