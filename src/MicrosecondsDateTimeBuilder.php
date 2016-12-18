<?php

/**
 * This file is part of the Utils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace Utils;

/**
 * Class MicrosecondsDateTimeBuilder
 *
 * @package Utils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/my-utils
 */
trait MicrosecondsDateTimeBuilder
{
    protected function microsecondsDateTime($asImmutable = true)
    {
        $microseconds = number_format(microtime(true), 6, '.', '');

        return $asImmutable ?
            \DateTimeImmutable::createFromFormat('U.u', $microseconds) :
            \DateTime::createFromFormat('U.u', $microseconds);
    }
}