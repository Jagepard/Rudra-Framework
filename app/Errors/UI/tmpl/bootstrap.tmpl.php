<?php
    use Rudra\Container\Facades\Rudra;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="<?= $title ?>"/>
    <meta name="author" content="Jagepard, jagepard@yandex.ru"/>
    <meta name="copyright" content="Jagepard">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $title ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://bootswatch.com/5/cerulean/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= Rudra::config()->get('url') ?>/assets/css/theme.css"/>
</head>
<body>
<div class="container" id="wrapper">
    <!-- Content here -->
    <div class="row">
        <div class="col text-center">
            <a class="td-decoration" href="<?= Rudra::config()->get('url') ?>" class="stretched-link"><h1 style="font-size: 600%;text-shadow: 4px 4px 2px rgba(10, 10, 10, 1);">TDcoRe</h1></a>
        </div>
    </div>

    <div class="row">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-0">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 py-3">
                        <li class="nav-item">
                            <a class="nav-link active" href="<?= Rudra::config()->get('url') ?>">Главная</a>
                        </li>
                    </ul>
<!--                    <form class="d-flex">-->
<!--                        <input class="form-control me-2" type="search" placeholder="Поиск..." aria-label="Search">-->
<!--                        <button class="btn btn-outline-success" type="submit">></button>-->
<!--                    </form>-->
                </div>
            </div>
        </nav>
    </div>

    <?= $content ?>

    <div class="row text-center bg-dark text-white td-text-12">
        <div class="col p-4">
            Created by <a  class="text-decoration-none" href="https://github.com/Jagepard"><i>Jagepard</i></a> <?= date('Y') ?>, powered by <i>Rudra Framework</i>
        </div>
    </div>
</div>

<!-- Option 2: Separate Popper and Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js" integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous" async></script>

</body>
</html>