[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Jagepard/Rudra-Framework/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Jagepard/Rudra-Framework/?branch=master)
[![Code Climate](https://codeclimate.com/github/Jagepard/Rudra-Framework/badges/gpa.svg)](https://codeclimate.com/github/Jagepard/Rudra-Framework)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/5c72a592d6914a8abc2ac91b3212062d)](https://www.codacy.com/app/Jagepard/Rudra-Framework?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Jagepard/Rudra-Framework&amp;utm_campaign=Badge_Grade)
-----
[![Latest Stable Version](https://poser.pugx.org/rudra/framework/v/stable)](https://packagist.org/packages/rudra/skeleton)
[![Total Downloads](https://poser.pugx.org/rudra/framework/downloads)](https://packagist.org/packages/rudra/skeleton)
![GitHub](https://img.shields.io/github/license/jagepard/Rudra-Framework.svg)

# Rudra-Framework

    composer create-project --prefer-dist rudra/skeleton=dev-master newapp
    cd newapp
    composer dumpautoload -o

    cd public
    php -S localhost:8000
    to run built-in web server
    
В newapp/app/config.yml настраиваем
'driver' - Способ работы с базой данных:
 PDO / Eloquent / mysqli или doctrine,
 Тип базы данных (Data Source Name или DSN), необходим
 для всех способов работы с БД кроме mysqli
 
    # БД
    database:
        active: pdo
        pdo:
            driver: PDO
            DSN: mysql
            host: localhost
            user: root
            password: ''
            name: rudra-skeleton-company
