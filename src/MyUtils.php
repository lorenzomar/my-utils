<?php

/**
 * This file is part of the MyUtils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace MyUtils;

/**
 * Class MyUtils.
 *
 * @package MyUtils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/my-utils
 */
class MyUtils
{
    /**
     * initMyCLabsEnum.
     * Partendo da una classe che estendo Enum e dal valore che dovrebbe assumere, tenta di inizializzare l'enum.
     *
     * @param string $classFqn
     * @param string $value
     * @param mixed  $default
     *
     * @return mixed
     */
    public static function initMyCLabsEnum($classFqn, $value, $default = null)
    {
        try {
            return call_user_func_array($classFqn . "::" . strtoupper($value), []);
        } catch (\Exception $e) {
            return $default;
        }
    }

    /**
     * uuid.
     * Factory method
     *
     * @return UuidUtils
     */
    public static function uuid()
    {
        return new UuidUtils();
    }

    /**
     * dateTime.
     *
     * @return DateTimeUtils
     */
    public static function dateTime()
    {
        return new DateTimeUtils();
    }

    /**
     * arr.
     *
     * @return ArrayUtils
     */
    public static function arr()
    {
        return new ArrayUtils();
    }

    /**
     * repository.
     *
     * @return RepositoryUtils
     */
    public static function repository()
    {
        return new RepositoryUtils();
    }
}