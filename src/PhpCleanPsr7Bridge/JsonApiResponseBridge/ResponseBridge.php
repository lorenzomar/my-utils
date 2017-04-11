<?php

/**
 * This file is part of the MyUtils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace MyUtils\PhpCleanPsr7Bridge\JsonApiResponseBridge;

use MyUtils\PhpCleanPsr7Bridge\ResponseBridgeInterface;
use PhpClean\UseCase\ResponseInterface as PhpCleanResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * Class ResponseBridge.
 *
 * @package MyUtils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/my-utils
 */
class ResponseBridge implements ResponseBridgeInterface
{
    /**
     * @var array
     */
    private $errorMappers = [];

    public function __construct(array $errorMappers = [])
    {

    }

    public function transform(PhpCleanResponseInterface $phpCleanResponse, PsrResponseInterface $psr7Response)
    {
        return $psr7Response;
    }
}