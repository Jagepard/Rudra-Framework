<?php

/**
 * Date: 14.07.15
 * Time: 11:41
 *
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2016, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

namespace App;

/**
 * Class Config Конфигурация сайта
 */
class Config
{

    /**
     * RBAC
     */
    const ROLE = [
        'admin'     => 1,
        'editor'    => 2,
        'moderator' => 3
    ];

    /**
     * Соль - для кеширования пароля
     */
    const SALT = '123';

    /**
     * Ключ reCaptcha для фронтенда
     */
    const CAPTHA_SITEKEY = '';

    /**
     * Ключ reCaptcha для бекэнда
     */
    const CAPTHA_SECRET = '';

    /**
     * Параметр использующийся для маршрутизации
     * REQUEST - $_SERVER['REQUEST_URI'] с правилом в .htaccess
     * RewriteRule ^(.*)$ index.php [L,QSA]
     * GET - $_GET['r'], в .htaccess необходимо указать правило
     * RewriteRule ^(.*)$ index.php?r=$1 [L,QSA]
     */
    const URI = 'REQUEST';

    /**
     * Шаблонизатор
     * twig или php
     */
    const TE = 'twig';

    /**
     * Public Path
     */
    const PUBLIC_PATH = BP . 'app/public';

    /**
     * Базовое пространство имен
     */
    const DEFAULT_NAMESPACE = 'App\Http\Controllers\\';

    /**
     * @var array
     * array['type']     string
     *                   Способ работы с базой данных
     *                   mysqli / PDO / Eloquent или Doctrine,
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
        'driver'   => 'PDO',
        'host'     => 'localhost',
        'user'     => 'root',
        'password' => '123',
        'name'     => 'jagepard',
    ];

    const HTTP_ERRORS = [
        '404' => ['App\Http\BaseController', 'error404'],
        '503' => ['App\Http\BaseController', 'error503'],
    ];
}
