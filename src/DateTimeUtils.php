<?php

/**
 * This file is part of the MyUtils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace MyUtils;

/**
 * Class DateTimeUtils.
 *
 * @package MyUtils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/my-utils
 */
class DateTimeUtils
{
    const DATE_TIME_MYSQL_FORMAT   = 'Y-m-d H:i:s';
    const DATE_TIME_ISO8601_FORMAT = 'Y-m-d\TH:i:s.uO';

    /**
     * initMicrosecondsDateTime.
     * Costruisce un oggetto DateTime completo di microsecondi. Quando si usa il costruttore di default, i microsecondi
     * non vengono impostati (quindi valgono sempre 0000)
     *
     * @param bool $asImmutable
     *
     * @return \DateTimeInterface
     */
    public function initMicrosecondsDateTime($asImmutable = true)
    {
        $microseconds = number_format(microtime(true), 6, '.', '');

        return $asImmutable ?
            \DateTimeImmutable::createFromFormat('U.u', $microseconds) :
            \DateTime::createFromFormat('U.u', $microseconds);
    }

    /**
     * formatMysqlDateTime.
     * Formatta un oggetto datetime nel formato giusto per essere salvato in MySql
     *
     * @param \DateTimeInterface $dateTime
     *
     * @return string
     */
    public function formatMysqlDateTime(\DateTimeInterface $dateTime)
    {
        return $dateTime->format(static::DATE_TIME_MYSQL_FORMAT);
    }

    /**
     * formatIso8601DateTime.
     * Formatta un oggetto datetime nel formato ISO8601 completo. Utile per formare le date in uscita dalle API.
     *
     * @param \DateTimeInterface $dateTime
     *
     * @return string
     */
    public function formatIso8601DateTime(\DateTimeInterface $dateTime)
    {
        return $dateTime->format(static::DATE_TIME_ISO8601_FORMAT);
    }
}