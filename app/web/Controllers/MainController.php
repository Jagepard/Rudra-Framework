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
        $this->twig('index', (array) $this->data());
    }
}
