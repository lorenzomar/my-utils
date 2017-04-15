<?php

/**
 * This file is part of the MyUtils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace MyUtils\PhpCleanJsonApiBridge;

use function GuzzleHttp\Psr7\stream_for;
use MyUtils\MyUtils;
use PhpClean\UseCase\ResponseInterface as PhpCleanResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * Class PhpCleanJsonApiResponseBridge.
 *
 * @package MyUtils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/my-utils
 */
class PhpCleanJsonApiResponseBridge
{
    /**
     * @var ErrorMapper[][]
     */
    private $errorMappers = [];

    /**
     * addErrorMappers.
     *
     * @param string $httpStatusCode
     * @param array  $mappers array composto da:
     *                        0 => phpCleanErrorKey
     *                        1 => phpCleanErrorCode
     *                        2 => jsonApiPointer
     *                        3 => jsonApiParameter
     *                        4 => jsonApiErrorCode
     *                        5 => includeMeta
     *
     * @return static
     */
    public function addErrorMappers($httpStatusCode, array $mappers)
    {
        foreach ($mappers as $mapper) {
            $this->errorMappers[$httpStatusCode][] = new ErrorMapper(
                $mapper[0],
                $mapper[1],
                $mapper[2],
                $mapper[3],
                $mapper[4],
                $mapper[5]
            );
        }

        return $this;
    }

    /**
     * transform.
     *
     * @param PhpCleanResponseInterface $phpCleanResponse
     * @param PsrResponseInterface      $psr7Response
     *
     * @return PsrResponseInterface
     */
    public function transform(PhpCleanResponseInterface $phpCleanResponse, PsrResponseInterface $psr7Response)
    {
        foreach ($this->errorMappers as $httpStatusCode => $mappers) {
            $errors = [];

            foreach ($mappers as $mapper) {
                if ($mapper->canManageThisResponse($phpCleanResponse)) {
                    $errors = array_merge($errors, $mapper->process($phpCleanResponse));
                }
            }

            if (!empty($errors)) {
                return $this->psr7ResponseWithJson(
                    $psr7Response,
                    MyUtils::jsonApi()->buildPayload(null, $errors),
                    $httpStatusCode
                );
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