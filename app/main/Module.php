<?php 

/**
 * Date: 16.07.15
 * Time: 12:41
 * 
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2016, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

namespace App\Main;

use Rudra\Controller;

/**
 * Class Module
 * @package Main
 * Родительский класс для контроллеров модуля
 */
class Module extends Controller
{
    /**
     * Директория модуля
     */
    const DIR = __DIR__;

    /**
     * @param iContainer $di
     */
    public function init(iContainer $di)
    {
        parent::init($di); // TODO: Change the autogenerated stub

        $this->setData('title', 'Title');
        $this->setData('sitekey', Config::CAPTHA_SITEKEY);
        $this->setData('di', $this->getDi());
        $this->setData('csrf_token', $this->getDi()->getSubSession('csrf_token', 3));
    }

    /**
     * Метод выполняется перед вызовом контроллера
     */
    public function before()
    {
        $this->getDi()->get('auth')->check();
    }

    public function after()
    {
        // Очищаем сессию от alert
        $this->getDi()->unsetSession('value');
        $this->getDi()->unsetSession('alert');
    }
}
