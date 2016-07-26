<?php

namespace Config;

/**
 * Date: 14.07.15
 * Time: 11:41
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2016, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */


/**
 * Class Config Конфигурация сайта
 */
class Config
{
    /**
     * Соль - для кеширования пароля
     */
    const SALT = '123';

    /**
     * Ключ reCaptcha для фронтенда
     */
    const CAPTHA_SITEKEY = "";

    /**
     * Ключ reCaptcha для бекэнда
     */
    const CAPTHA_SECRET = "";

    /**
     * Параметр использующийся для маршрутизации
     * REQUEST - $_SERVER['REQUEST_URI'] с правилом в .htaccess
     * RewriteRule ^(.*)$ index.php [L,QSA]
     * GET - $_GET['r'], в .htaccess необходимо указать правило
     * RewriteRule ^(.*)$ index.php?r=$1 [L,QSA]
     */
    const URI = 'REQUEST';

    /**7
     * Шаблонизатор
     * twig или false
     */
    const TE = 'twig';

    /**
     * @var array
     * array['type']     string
     *                   Способ работы с базой данных
     *                   PDO / NotORM / mysqli или doctrine,
     *                   либо false если БД не используется
     * array['host']     string
     *                   Имя хоста или IP адрес
     * array['user']     string
     *                   Имя пользователя БД
     * array['password'] string
     *                   Пароль пользователя БД
     * array['name']     string
     *                   Имя БД
     */
    const DB = [
        'type'     => 'NotORM',
        'host'     => '',
        'user'     => '',
        'password' => '',
        'name'     => '',
    ];
}
