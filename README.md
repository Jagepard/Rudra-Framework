[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Jagepard/Rudra-Framework/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Jagepard/Rudra-Framework/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/rudra/framework/v/stable)](https://packagist.org/packages/rudra/framework)
[![Total Downloads](https://poser.pugx.org/rudra/framework/downloads)](https://packagist.org/packages/rudra/framework)
[![License](https://poser.pugx.org/rudra/framework/license)](https://packagist.org/packages/rudra/framework)
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
