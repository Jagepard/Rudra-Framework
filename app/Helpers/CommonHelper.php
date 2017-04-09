<?php

namespace App\Helpers;

/**
 * Date: 23.12.16
 * Time: 15:01
 *
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2016, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

class CommonHelper
{

    /**
     * @param $array
     * @param $arrayKey
     *
     * @return int|string
     */
    public function searchArrayKey($array, $arrayKey)
    {
        foreach ($array as $key => $value) {
            if (stristr($key, $arrayKey) !== false) {
                return $key;
            }
        }
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function dateFormat($value)
    {
        $date  = \DateTime::createFromFormat('d.m.Y H:i', $value);
        $value = $date->format('Y-m-d H:i');

        return $value;
    }

    /**
     * @param $value
     *
     * @return int
     */
    public function checkbox($value)
    {
        return ($value) ? '1' : '0';
    }
}
