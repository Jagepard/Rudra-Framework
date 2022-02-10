<?php
use Rudra\Container\Facades\Rudra;
use Rudra\Container\Facades\Request;
use Rudra\Container\Facades\Session;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="<?= $title ?>"/>
    <meta name="keywords" content="<?= $title ?>" />
    <meta name="author" content="Jagepard, jagepard@yandex.ru"/>
    <meta name="copyright" content="Jagepard">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="yandex-verification" content="e456ede396e85545" />

    <meta property='og:title' content='<?= $title ?>' />
    <meta property='og:description' content='<?= $title ?>'/>
    <meta property='og:site_name' content='TDcoRe.ru'/>
    <meta property='og:type' content='article'/>
    <?php if (isset($item)): ?>
<meta property="og:image" content="<?= $item['front_image']?>"/>
    <?php endif; ?>

    <title><?= $title ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poiret+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= Rudra::config()->get('url') ?>/assets/css/styles.css"/>

    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.6.22/dist/css/uikit.min.css"/>

    <!-- UIkit JS -->
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.6.22/dist/js/uikit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.6.22/dist/js/uikit-icons.min.js"></script>

    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(82741198, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true
        });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/82741198" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-41132271-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-41132271-1');
    </script>

    <?php if (Rudra::config()->get('environment') === 'development') {
        echo $debugbar->renderHead();
    } ?>
</head>
<body>

<div>
    <div class="uk-section uk-padding-remove uk-margin-top">
        <div class="uk-container uk-text-center">

            <a href="<?= Rudra::config()->get('url') ?>" class="stretched-link"><h1 style="    font-size    : 800%;
    color        : #454545;
    padding-left : 60px;
    text-shadow  : 4px 4px 2px rgba(10, 10, 10, 1);
    font-family      : 'Poiret One', cursive;"><b>TDcoRe</b></h1></a>
        </div>
    </div>
</div>

<div class="uk-container uk-margin-small-top">
    <nav class="uk-navbar-container uk-navbar-transparent" uk-navbar>
        <div class="uk-navbar-left">

            <div class="uk-navbar-item">
                <form class="d-flex" action="<?= Rudra::config()->get("url") ?>/search" method="post">
                    <input class="uk-input uk-form-width-medium" type="text" name="search" placeholder="Поиск..." aria-label="Search">
                    <input type='hidden' name='redirect' value='<?= Request::server()->get('REQUEST_URI') ?>'>
                    <input type='hidden' name='csrf_field' value='<?= Session::get('csrf_token')[0]; ?>'>
                    <button class="uk-button uk-button-default" type="submit">Поиск<i class="bi bi-search"></i></button>
                </form>
            </div>

            <ul class="uk-navbar-nav">

            </ul>

        </div>
    </nav>
</div>

    <?= $content ?>

<div class="uk-section-secondary uk-preserve-color uk-margin-top">
    <div class="uk-section uk-light">
        <div class="uk-container uk-text-center">
            <p>Created by <a class="text-decoration-none" href="https://github.com/Jagepard"><i>Jagepard</i></a> <?= date('Y') ?>, powered by <i>Rudra Framework</i></p>
        </div>
    </div>
</div>

    <?php if (Rudra::config()->get('environment') === 'development') {
        echo $debugbar->render();
    } ?>
</body>
</html>