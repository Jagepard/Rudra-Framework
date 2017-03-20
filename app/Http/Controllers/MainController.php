<?php
/**
 * Created by PhpStorm.
 * User: d
 * Date: 20.03.17
 * Time: 15:46
 */

namespace App\Http\Controllers;


use App\Http\BaseController;

class MainController extends BaseController
{
    /**
     * @Routing(url = '')
     */
    public function actionIndex()
    {
        ddd(123);
    }
}