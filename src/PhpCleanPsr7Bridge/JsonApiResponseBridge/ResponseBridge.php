<?php

/**
 * This file is part of the MyUtils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace MyUtils\PhpCleanPsr7Bridge\JsonApiResponseBridge;

use function GuzzleHttp\Psr7\stream_for;
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
                return $this->psr7ResponseWithJson($psr7Response, ['errors' => $errors], $httpStatusCode);
            }
        }

        return $psr7Response;
    }

    private function psr7ResponseWithJson(PsrResponseInterface $response, $data, $status = null, $encodingOptions = 0)
    {
        $json = json_encode($data, $encodingOptions);

        // Ensure that the json encoding passed successfully
        if ($json === false) {
            throw new \RuntimeException(json_last_error_msg(), json_last_error());
        }

        $response = $response->withBody(stream_for($json))
                             ->withHeader('Content-Type', 'application/json;charset=utf-8');

        return isset($status) ? $response->withStatus($status) : $response;
    }
}