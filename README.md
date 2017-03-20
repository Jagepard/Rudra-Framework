# Rudra-Framework

    composer create-project --prefer-dist rudra/framework=dev-master newapp
    cd newapp
    composer dumpautoload -o

В newapp/app/config.php настраиваем
'driver' - Способ работы с базой данных:
 PDO / Eloquent / mysqli или doctrine,
 
     const DB = [
         'type'     => 'mysqli',
         'host'     => 'localhost',
         'user'     => 'root',
         'password' => '',
         'name'     => '',
     ];
