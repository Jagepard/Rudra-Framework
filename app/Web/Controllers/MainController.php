<?php

namespace App\Web\Controllers;

use App\Web\WebController;
use Rudra\View\ViewFacade as View;

class MainController extends WebController
{
    /**
     * @Routing(url = '')
     */
    public function index()
    {
        $content = View::cache(['index', 'now']) ?? View::view(["index", 'mainpage'], []);

        $this->data->set([
            "title"   => "Rudra Framework",
            "content" => $content
        ]);

        View::render("layout", $this->data->get());
    }
}
