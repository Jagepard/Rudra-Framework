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
        $this->setData('content', $this->view('index', $this->getData()));
        
        return $this->render('layout', $this->getData());
    }
}
