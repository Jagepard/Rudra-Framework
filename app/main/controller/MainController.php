<?php

namespace Main;

use Main\Model\Categories;
use Main\Model\Items;

/**
 * Class MainController
 * @package Main
 */
class MainController extends Main
{
    public function actionIndex()
    {
        echo $this->twig->render('main.html.twig', $this->data);
    }
}
