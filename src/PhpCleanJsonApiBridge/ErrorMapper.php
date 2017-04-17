<?php

/**
 * This file is part of the MyUtils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace MyUtils\PhpCleanJsonApiBridge;

use MyUtils\MyUtils;
use PhpClean\UseCase\ResponseInterface;

/**
 * Class ErrorMapper.
 *
 * @package MyUtils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/my-utils
 */
class ErrorMapper
{
    /**
     * @var string
     */
    private $phpCleanErrorKey;

    /**
     * @var string|null
     */
    private $phpCleanErrorCode;

    /**
     * @var string|null
     */
    private $jsonApiErrorCode;

    /**
     * @var string
     */
    private $jsonApiSourcePointer;

    /**
     * @var string
     */
    private $jsonApiParameter;

    /**
     * @var bool
     */
    private $includeMeta;

    public function __construct(
        $phpCleanErrorKey,
        $phpCleanErrorCode = null,
        $jsonApiSourcePointer = null,
        $jsonApiParameter = null,
        $jsonApiErrorCode,
        $includeMeta = true
    ) {
        $this->phpCleanErrorKey     = $phpCleanErrorKey;
        $this->phpCleanErrorCode    = $phpCleanErrorCode;
        $this->jsonApiSourcePointer = $jsonApiSourcePointer;
        $this->jsonApiParameter     = $jsonApiParameter;
        $this->jsonApiErrorCode     = $jsonApiErrorCode;
        $this->includeMeta          = $includeMeta;
    }

    /**
     * phpCleanErrorKey.
     *
     * @return string
     */
    public function phpCleanErrorKey()
    {
        return $this->phpCleanErrorKey;
    }

    /**
     * phpCleanErrorCode.
     *
     * @return string|null
     */
    public function phpCleanErrorCode()
    {
        return $this->phpCleanErrorCode;
    }

    /**
     * canManageThisResponse.
     *
     * @param ResponseInterface $response
     *
     * @return bool
     */
    public function canManageThisResponse(ResponseInterface $response)
    {
        return is_null($this->phpCleanErrorCode) ?
            $response->has($this->phpCleanErrorKey) :
            $response->hasError($this->phpCleanErrorKey, $this->phpCleanErrorCode);
    }

    /**
     * process.
     *
     * @param ResponseInterface $response
     *
     * @return array
     */
    public function process(ResponseInterface $response)
    {
        if (is_null($this->phpCleanErrorCode)) {
            return MyUtils::jsonApi()
                          ->buildError($this->jsonApiErrorCode, $this->jsonApiSourcePointer, $this->jsonApiParameter);
        }

        $error = $response->getError($this->phpCleanErrorKey, $this->phpCleanErrorCode);

        return MyUtils::jsonApi()->buildError(
            $this->jsonApiErrorCode,
            $this->jsonApiSourcePointer,
            $this->jsonApiParameter,
            $this->includeMeta ? $error['meta'] : []
        );
    }
}