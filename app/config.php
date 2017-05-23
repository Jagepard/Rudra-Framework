<?php

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
    const CAPTCHA_SITE_KEY = '';

    /**
     * Ключ reCaptcha для бекэнда
     */
    const CAPTCHA_SECRET = '';

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
    const DEFAULT_NAMESPACE = 'App\Http\\';

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
        'password' => '',
        'name'     => '',
    ];

    const HTTP_ERRORS = [
        '404' => [
            'controller'  => 'App\\Http\\HttpController',
            'method'      => 'error404'
        ],
        '503' => [
            'controller'  => 'App\\Http\\HttpController',
            'method'      => 'error503'
        ],
    ];
}
