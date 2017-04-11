<?php

/**
 * This file is part of the MyUtils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace MyUtils\PhpCleanPsr7Bridge;

use PhpClean\UseCase\ResponseInterface as PhpCleanResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * Interface ResponseBridgeInterface.
 *
 * @package MyUtils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/my-utils
 */
interface ResponseBridgeInterface
{
    /**
     * transform.
     *
     * @param PhpCleanResponseInterface $phpCleanResponse
     * @param PsrResponseInterface      $psr7Response
     *
     * @return PsrResponseInterface
     */
    public function transform(PhpCleanResponseInterface $phpCleanResponse, PsrResponseInterface $psr7Response);
}