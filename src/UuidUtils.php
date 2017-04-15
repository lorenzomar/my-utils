<?php

/**
 * This file is part of the MyUtils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace MyUtils;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class UuidUtils.
 *
 * @package MyUtils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/my-utils
 */
class UuidUtils
{
    /**
     * initUuids.
     * Trasforma l'array di stringhe in ingresso in una lista di Uuid in uscita
     *
     * @param string[] $ids
     *
     * @return UuidInterface[]
     */
    public function initUuids(array $ids = [])
    {
        return array_map(function ($id) {
            return Uuid::fromString($id);
        }, $ids);
    }

    /**
     * mysqlOptimizeUuid1.
     * Ottimizza uno UUID1 per essere storato efficentemente su Mysql (vedi articoli su Percona per dettagli).
     * Se in ingresso riceve un array lo interpreta come una lista di uuid1 e li ottimizza tutti.
     *
     * @param UuidInterface|UuidInterface[]|string|string[] $uuid1
     *
     * @return string|string[]
     */
    public function mysqlOptimizeUuid1($uuid1)
    {
        $isSingle = $uuid1 instanceof UuidInterface;
        $uuid1    = $isSingle ? [$uuid1] : $uuid1;
        $uuid1    = array_map(function ($uuid) {
            $v = ($uuid instanceof UuidInterface) ? (string)$uuid : $uuid;

            return hex2bin(substr($v, 14, 4) .
                substr($v, 9, 4) .
                substr($v, 0, 8) .
                substr($v, 19, 4) .
                substr($v, 24)
            );
        }, $uuid1);

        return $isSingle ? array_shift($uuid1) : $uuid1;
    }

    /**
     * mysqlUnoptimizeUuid1.
     * Partendo da uno uuid1 ottimizzato ritorna allo uuid1 reale.
     * Se in ingresso riceve un array lo interpreta come una lista e li deottimizza tutti.
     *
     * @param string|string[] $optimizedUuid1
     *
     * @return string|string[]
     */
    public function mysqlUnoptimizeUuid1($optimizedUuid1)
    {
        $isSingle       = !is_array($optimizedUuid1);
        $optimizedUuid1 = $isSingle ? [$optimizedUuid1] : $optimizedUuid1;
        $optimizedUuid1 = array_map(function ($optimizedUuid) {
            $v = bin2hex($optimizedUuid);

            return substr($v, 8, 8) . '-' .
                substr($v, 4, 4) . '-' .
                substr($v, 0, 4) . '-' .
                substr($v, 16, 4) . '-' .
                substr($v, 20);
        }, $optimizedUuid1);

        return $isSingle ? array_shift($optimizedUuid1) : $optimizedUuid1;
    }
}