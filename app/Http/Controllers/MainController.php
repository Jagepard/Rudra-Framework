<?php

namespace App\Http\Controllers;

use App\Http\BaseController;

class MainController extends BaseController
{
    /**
     * @Routing(url = '')
     */
    public function actionIndex()
    {
        ddd('Hello World!!!');
    }
}
