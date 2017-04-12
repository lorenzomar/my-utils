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
 * 500 => [
 * 'map' => [
 * [
 * 'errorKey'   => 'general',
 * 'errorCodes' => ['save_error'],
 * ],
 * ],
 * ],
 *
 * @package MyUtils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/my-utils
 */
class ResponseBridge implements ResponseBridgeInterface
{
    /**
     * @var ErrorMapper[][]
     */
    private $errorMappers = [];

    public function addSourcePointerErrorMapper(
        $httpStatusCode,
        $phpCleanErrorKey,
        array $phpCleanErrorCodes = [],
        $jsonApiSourcePointer,
        array $jsonApiErrorCodes = [],
        $includeMeta = true
    ) {
        $this->errorMappers[$httpStatusCode][] = ErrorMapper::sourcePointer(
            $phpCleanErrorKey,
            $phpCleanErrorCodes,
            $jsonApiSourcePointer,
            $jsonApiErrorCodes,
            $includeMeta
        );
    }

    public function addParameterErrorMapper(
        $httpStatusCode,
        $phpCleanErrorKey,
        array $phpCleanErrorCodes = [],
        $jsonApiParameter,
        array $jsonApiErrorCodes = [],
        $includeMeta = true
    ) {
        $this->errorMappers[$httpStatusCode][] = ErrorMapper::parameter(
            $phpCleanErrorKey,
            $phpCleanErrorCodes,
            $jsonApiParameter,
            $jsonApiErrorCodes,
            $includeMeta
        );
    }

    public function transform(PhpCleanResponseInterface $phpCleanResponse, PsrResponseInterface $psr7Response)
    {
        if ($phpCleanResponse->isSuccess()) {
            return $psr7Response;
        }

        foreach ($this->errorMappers as $httpStatusCode => $mappers) {
            $errors = [];

            foreach ($mappers as $mapper) {
                if ($mapper->canManageThisResponse($phpCleanResponse)) {
                    $errors = array_merge($errors, $mapper->process($phpCleanResponse));
                }
            }

            if (!empty($errors)) {
                return $psr7Response->withJson(['errors' => $errors], $httpStatusCode);
                return $this->withJsonApiErrors(
                    $includeErrorDetails ? $errors : [],
                    $httpStatusCode
                );
            }


            $errors              = [];
            $errors              = [];
            $includeErrorDetails = isset($rules['includeErrorDetails']) ? (bool) $rules['includeErrorDetails'] : true;

            foreach ($rules['map'] as $map) {
                if (!$response->has($map['errorKey'])) {
                    continue;
                }

                $errorKey         = $map['errorKey'];
                $errorCodes       = (!isset($map['errorCodes']) || is_null($map['errorCodes']) || empty($map['errorCodes'])) ?
                    null :
                    $map['errorCodes'];
                $callback         = (!isset($map['callback']) || !is_callable($map['callback'])) ? null : $map['callback'];
                $jsonApiErrorCode = isset($map['jsonApiErrorCode']) ? $map['jsonApiErrorCode'] : null;
                $sourcePointer    = isset($map['jsonApiSourcePointer']) ? $map['jsonApiSourcePointer'] : null;
                $sourceParameter  = isset($map['jsonApiSourceParameter']) ? $map['jsonApiSourceParameter'] : null;
                $includeMeta      = isset($map['includeMeta']) ? (bool) $map['includeMeta'] : true;

                if ($callback && $response->has($errorKey)) {
                    $errors = array_merge($errors, call_user_func_array($callback, [$response->get($errorKey), $this]));
                } else {
                    if ($errorCodes === null && $response->has($errorKey)) {
                        $errors[] = $this->makeJsonApiError($jsonApiErrorCode, $sourcePointer, $sourceParameter);
                    } else {
                        foreach ($errorCodes as $errorCode) {
                            if ($response->hasError($errorKey, $errorCode)) {
                                $error = $response->getError($errorKey, $errorCode);

                                $errors[] = $this->makeJsonApiError(
                                    $errorCode,
                                    $sourcePointer,
                                    $sourceParameter,
                                    $includeMeta ? $error['meta'] : []
                                );
                            }
                        }
                    }
                }
            }

            if (!empty($errors)) {
                return $this->withJsonApiErrors(
                    $includeErrorDetails ? $errors : [],
                    $httpStatusCode
                );
            }
        }

        return $psr7Response;
    }

    private function psr7ResponseWithJson(PsrResponseInterface $response, $data, $status = null, $encodingOptions = 0)
    {
        $response = $this->withBody(new Body(fopen('php://temp', 'r+')));
        $response->body->write($json = json_encode($data, $encodingOptions));

        // Ensure that the json encoding passed successfully
        if ($json === false) {
            throw new \RuntimeException(json_last_error_msg(), json_last_error());
        }

        $responseWithJson = $response->withHeader('Content-Type', 'application/json;charset=utf-8');
        if (isset($status)) {
            return $responseWithJson->withStatus($status);
        }
        return $responseWithJson;
    }
}