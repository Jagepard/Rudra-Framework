<?php

namespace App\Http\Controllers;

use App\Http\HttpController;

class MainController extends HttpController
{

    /**
     * @Routing(url = '')
     */
    public function actionIndex()
    {
        return $this->twig('index.html.twig', $this->data());
    }
}
