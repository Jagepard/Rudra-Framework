<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace App\Containers\Demo\UI;

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
