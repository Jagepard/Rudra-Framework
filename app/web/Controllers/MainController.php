<?php

namespace App\Web\Controllers;

use App\Web\WebController;

class MainController extends WebController
{

    /**
     * @Routing(url = '')
     */
    public function actionIndex()
    {
        return $this->twig('index.html.twig', (array) $this->data());
    }
}
