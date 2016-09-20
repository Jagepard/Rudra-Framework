<?php 

namespace App\Main\Controller;

use App\Main\Module;

/**
 * Class MainController
 * @package Main
 */
class MainController extends Module
{
    public function actionIndex()
    {
        echo 'Hello World!';
    }
}
