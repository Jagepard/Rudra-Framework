[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Jagepard/Rudra-Framework/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Jagepard/Rudra-Framework/?branch=master)
[![Code Climate](https://codeclimate.com/github/Jagepard/Rudra-Framework/badges/gpa.svg)](https://codeclimate.com/github/Jagepard/Rudra-Framework)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/5c72a592d6914a8abc2ac91b3212062d)](https://www.codacy.com/app/Jagepard/Rudra-Framework?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Jagepard/Rudra-Framework&amp;utm_campaign=Badge_Grade)
[![Latest Stable Version](https://poser.pugx.org/rudra/framework/v/stable)](https://packagist.org/packages/rudra/framework)
[![Total Downloads](https://poser.pugx.org/rudra/framework/downloads)](https://packagist.org/packages/rudra/framework)
[![License](https://poser.pugx.org/rudra/framework/license)](https://packagist.org/packages/rudra/framework

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/3af6d532-ddb3-43b2-803c-3acac133488e/big.png)](https://insight.sensiolabs.com/projects/3af6d532-ddb3-43b2-803c-3acac133488e)
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
