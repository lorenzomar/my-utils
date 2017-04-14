<?php

/**
 * This file is part of the MyUtils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace MyUtils\PhpCleanPsr7Bridge\JsonApiResponseBridge;

use PhpClean\UseCase\ResponseInterface;
use Ramsey\Uuid\Uuid;

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
     * @var array
     */
    private $phpCleanErrorCodes;

    /**
     * @var array
     */
    private $jsonApiErrorCodes;

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

    private function __construct(
        $phpCleanErrorKey,
        array $phpCleanErrorCodes = [],
        array $jsonApiErrorCodes = [],
        $includeMeta
    ) {
        if (empty($phpCleanErrorCodes) && empty($jsonApiErrorCodes)) {
            throw new \RuntimeException("Both phpCleanErrorCodes and jsonApiErrorCodes cannot be empty. Set at least one of them.");
        }

        if (empty($phpCleanErrorCodes) && count($jsonApiErrorCodes) > 1) {
            throw new \RuntimeException("If phpCleanErrorCodes is empty than jsonApiErrorCodes can contains only 1 value.");
        }

        $this->phpCleanErrorKey   = $phpCleanErrorKey;
        $this->phpCleanErrorCodes = $phpCleanErrorCodes;
        $this->jsonApiErrorCodes  = $jsonApiErrorCodes;
        $this->includeMeta        = empty($phpCleanErrorCodes) ? false : (bool)$includeMeta;
    }

    /**
     * sourcePointer.
     *
     * @param string $phpCleanErrorKey
     * @param array  $phpCleanErrorCodes
     * @param string $jsonApiSourcePointer
     * @param array  $jsonApiErrorCodes
     * @param bool   $includeMeta
     *
     * @return static
     */
    public static function sourcePointer(
        $phpCleanErrorKey,
        array $phpCleanErrorCodes = [],
        $jsonApiSourcePointer,
        array $jsonApiErrorCodes = [],
        $includeMeta
    ) {
        $s                       = new static($phpCleanErrorKey, $phpCleanErrorCodes, $jsonApiErrorCodes, $includeMeta);
        $s->jsonApiSourcePointer = $jsonApiSourcePointer;

        return $s;
    }

    /**
     * parameter.
     *
     * @param string $phpCleanErrorKey
     * @param array  $phpCleanErrorCodes
     * @param string $jsonApiParameter
     * @param array  $jsonApiErrorCodes
     * @param bool   $includeMeta
     *
     * @return static
     */
    public static function parameter(
        $phpCleanErrorKey,
        array $phpCleanErrorCodes = [],
        $jsonApiParameter,
        array $jsonApiErrorCodes = [],
        $includeMeta
    ) {
        $s                   = new static($phpCleanErrorKey, $phpCleanErrorCodes, $jsonApiErrorCodes, $includeMeta);
        $s->jsonApiParameter = $jsonApiParameter;

        return $s;
    }

    /**
     * withoutParameters.
     *
     * @param string $phpCleanErrorKey
     * @param array  $phpCleanErrorCodes
     * @param array  $jsonApiErrorCodes
     * @param bool   $includeMeta
     *
     * @return static
     */
    public static function withoutParameters(
        $phpCleanErrorKey,
        array $phpCleanErrorCodes = [],
        array $jsonApiErrorCodes = [],
        $includeMeta
    ) {
        return new static($phpCleanErrorKey, $phpCleanErrorCodes, $jsonApiErrorCodes, $includeMeta);
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
        if (empty($this->phpCleanErrorCodes)) {
            return $response->has($this->phpCleanErrorKey);
        } else {
            foreach ($this->phpCleanErrorCodes as $errorCode) {
                if ($response->hasError($this->phpCleanErrorKey, $errorCode)) {
                    return true;
                }
            }
        }

        return false;
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
        $errors = [];

        if (empty($this->phpCleanErrorCodes)) {
            $errors[] = $this->makeJsonApiError(array_shift($this->jsonApiErrorCodes), []);
        } else {
            foreach ($this->phpCleanErrorCodes as $i => $errorCode) {
                if ($response->hasError($this->phpCleanErrorKey, $errorCode)) {
                    $error = $response->getError($this->phpCleanErrorKey, $errorCode);

                    $errors[] = $this->makeJsonApiError(
                        isset($this->jsonApiErrorCodes[$i]) ? $this->jsonApiErrorCodes[$i] : $errorCode,
                        $error
                    );
                }
            }
        }

        return $errors;
    }

    private function makeJsonApiError($code, array $error)
    {
        $e = [
            'id'   => (string)Uuid::uuid1(),
            'code' => $code,
        ];

        if ($this->jsonApiSourcePointer) {
            $e['source']['pointer'] = $this->jsonApiSourcePointer;
        } elseif ($this->jsonApiParameter) {
            $e['source']['parameter'] = $this->jsonApiParameter;
        }

        if ($this->includeMeta && $error['meta']) {
            $e['meta'] = $error['meta'];
        }

        return $e;
    }
}