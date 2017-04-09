<?php

/**
 * This file is part of the MyUtils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace MyUtils;

/**
 * Class ArrayUtils.
 *
 * @package MyUtils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/my-utils
 */
class ArrayUtils
{
    /**
     * isAssoc.
     * Controlla se l'array passato contiene delle chiavi che non sono numeriche.
     *
     * @param array $array
     *
     * @return bool
     */
    public function isAssoc(array $array)
    {
        $isAssoc = true;

        foreach (array_keys($array) as $key) {
            $isAssoc = !ctype_digit((string)$key) && $isAssoc;
        }

        return $isAssoc;
    }
}