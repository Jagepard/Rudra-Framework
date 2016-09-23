<?php 

namespace App\Main\Controller;

use App\Main\Module;

/**
 * Class MainController
 * @package Main
 */
class MainController extends Module
{

    /**
     * @Routing(url = '')
     */
    public function actionIndex()
    {
        echo 'Hello World!';
    }

}
