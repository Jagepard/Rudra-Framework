<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace App\Ship\Utils;

class Theme
{
    public const Brite = 'Brite';
    public const Cerulean = 'Cerulean';
    public const Cosmo = 'Cosmo';
    public const Cyborg = 'Cyborg';
    public const Darkly = 'Darkly';
    public const Flatly = 'Flatly';
    public const Journal = 'Journal';
    public const Litera = 'Litera';
    public const Lumen = 'Lumen';
    public const Lux = 'Lux';
    public const Materia = 'Materia';
    public const Minty = 'Minty';
    public const Morph = 'Morph';
    public const Pulse = 'Pulse';
    public const Quartz = 'Quartz';
    public const Sandstone = 'Sandstone';
    public const Simplex = 'Simplex';
    public const Sketchy = 'Sketchy';
    public const Slate = 'Slate';
    public const Solar = 'Solar';
    public const Spacelab = 'Spacelab';
    public const Superhero = 'Superhero';
    public const United = 'United';
    public const Vapor = 'Vapor';
    public const Yeti = 'Yeti';
    public const Zephyr = 'Zephyr';

    public static function toArray(): array
    {
        return (new \ReflectionClass(self::class))->getConstants();
    }
}
