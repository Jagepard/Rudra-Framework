<?php

namespace App\Errors;

use Rudra\Controller\Controller;
use Rudra\View\ViewFacade as View;

class ErrorsController extends Controller
{
    public function init()
    {
        View::setup([
            "base.path"      => dirname(__DIR__) . '/',
            "engine"         => "native",
            "view.path"      => "Errors/UI/tmpl",
            "file.extension" => "tmpl.php",
        ]);

        $this->data->set([
            "title" => "Rudra Framework",
        ]);
    }
}
