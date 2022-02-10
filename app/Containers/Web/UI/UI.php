<?php


namespace App\Containers\Web\UI;

use Rudra\Container\Facades\Rudra;

class UI
{
    public static function renderLinks(array $links, string $page, $pg_limit, $uri)
    {
        $last = array_key_last($links) + 1;
        ?>
        <!-- FIRST -->
        <?php if ($links[0] != $page): ?>
        <li class="page-item"><a href="<?= Rudra::config()->get("url") ?><?= $uri . $links[0] ?>" class="page-link"><<</a></li>
        <?php endif; ?>
        <?php foreach ($links as $link): ?>
            <?php if (($link < $page) && ($link >= ($page - $pg_limit))): ?>
            <li class="page-item"><a href="<?= Rudra::config()->get("url") ?><?= $uri . $link ?>" class="page-link"><?= $link ?></a></li>
            <?php endif; ?>

            <?php if ($link == $page): ?>
            <li class="page-item uk-active"><a href="<?= Rudra::config()->get("url") ?><?= $uri . $link ?>" class="page-link"><?= $link ?></a></li>
            <?php endif; ?>

            <?php if (($link > $page) && ($link <= ($page + $pg_limit))): ?>
                <li class="page-item"><a href="<?= Rudra::config()->get("url") ?><?= $uri . $link ?>" class="page-link"><?= $link ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
        <!-- LAST -->
        <?php if ($last != $page): ?>
        <li class="page-item"><a href="<?= Rudra::config()->get("url") ?><?= $uri . $last ?>" class="page-link">>></a></li>
        <?php endif; ?>
        <?php
    }
}
