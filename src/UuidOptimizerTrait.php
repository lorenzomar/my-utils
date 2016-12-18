<?php

/**
 * This file is part of the Utils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace Utils;

use Ramsey\Uuid\UuidInterface;

/**
 * Trait UuidOptimizerTrait
 *
 * @package Utils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/utils
 */
trait UuidOptimizerTrait
{
    /**
     * optimizeUuid
     *
     * @param UuidInterface|string $uuid
     *
     * @return string
     */
    protected function optimizeUuid($uuid)
    {
        $id = ($uuid instanceof UuidInterface) ? (string)$uuid : $uuid;
        $id = substr($id, 14, 4) . substr($id, 9, 4) . substr($id, 0, 8) . substr($id, 19, 4) . substr($id, 24);

        return hex2bin($id);
    }
}