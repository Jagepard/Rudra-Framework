<?php

/**
 * Date: 20.03.17
 * Time: 15:46
 *
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2016, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
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
        ddd('Hello World!!!');
    }
}
