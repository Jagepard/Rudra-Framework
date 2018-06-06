[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Jagepard/Rudra-Framework/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Jagepard/Rudra-Framework/?branch=master)
[![Code Climate](https://codeclimate.com/github/Jagepard/Rudra-Framework/badges/gpa.svg)](https://codeclimate.com/github/Jagepard/Rudra-Framework)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/5c72a592d6914a8abc2ac91b3212062d)](https://www.codacy.com/app/Jagepard/Rudra-Framework?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Jagepard/Rudra-Framework&amp;utm_campaign=Badge_Grade)
-----
[![Latest Stable Version](https://poser.pugx.org/rudra/framework/v/stable)](https://packagist.org/packages/rudra/framework)
[![Total Downloads](https://poser.pugx.org/rudra/framework/downloads)](https://packagist.org/packages/rudra/framework)
[![License: GPL-3.0-or-later](https://img.shields.io/badge/license-GPL--3.0--or--later-498e7f.svg)](https://www.gnu.org/licenses/gpl-3.0)

# Rudra-Framework

    composer create-project --prefer-dist rudra/framework=dev-master newapp
    cd newapp
    composer dumpautoload -o

В newapp/app/config.yml настраиваем
'driver' - Способ работы с базой данных:
 PDO / Eloquent / mysqli или doctrine,
 Тип базы данных (Data Source Name или DSN), необходим
 для всех способов работы с БД кроме mysqli
 
    # БД
    database:
        driver: PDO
        DSN: mysql
        host: localhost
        user: root
        password: '123'
        name: somedb
