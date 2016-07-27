# Rudra-Framework

composer create-project --prefer-dist rudra/framework=dev-master newapp

cd newapp

composer dumpautoload -o

В newapp/app/config/Config.php настраиваем
'type' - Способ работы с базой данных
 PDO / NotORM / mysqli или doctrine,
 
    const DB = [
        'type'     => 'mysqli',
        'host'     => 'localhost',
        'user'     => 'root',
        'password' => '',
        'name'     => '',
    ];
    
    
Если 'type' NotORM, то подключаем пакет:
composer require vrana/notorm=dev-master

Если 'type' doctrine, то подключаем пакет:
composer require doctrine/orm=v2.5.0
а также в корень сайта добавляем config-doctrine.php
с содержимым:

   use App\Config\Config;

    function doctrine()
    {
        return ['paths' => [
            '/path/to/app/main/entity'
        ],
            'isDevMode' => true,
    
    // the connection configuration
            'dbParams' => [
                'driver' => 'pdo_mysql',
                'user' => Config::DB['user'],
                'password' => Config::DB['password'],
                'dbname' => Config::DB['name'],
            ]
        ];
    }
