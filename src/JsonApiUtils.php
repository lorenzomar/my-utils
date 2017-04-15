<?php

/**
 * This file is part of the MyUtils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace MyUtils;

use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;

/**
 * Class JsonApiUtils.
 *
 * @package MyUtils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/my-utils
 */
class JsonApiUtils
{
    /**
     * getRequestParsedBody.
     *
     * @param ServerRequestInterface $request
     *
     * @return mixed|null
     */
    public function getRequestParsedBody(ServerRequestInterface $request)
    {
        $body = $request->getParsedBody();

        return (!is_array($body) || !isset($body['data']) || !is_array($body['data'])) ? null : $body['data'];
    }

    /**
     * buildError.
     *
     * @param string      $code
     * @param null|string $pointer
     * @param null|string $parameter
     * @param array       $meta
     *
     * @return array
     */
    public function buildError($code, $pointer = null, $parameter = null, array $meta = [])
    {
        $e = [
            'id'   => (string)Uuid::uuid1(),
            'code' => $code,
        ];

        if ($pointer) {
            $e['source']['pointer'] = $pointer;
        } elseif ($parameter) {
            $e['source']['parameter'] = $parameter;
        }

        if (!empty($meta)) {
            $e['meta'] = $meta;
        }

        return $e;
    }

    /**
     * buildPayload.
     *
     * @param array|null $data
     * @param array|null $errors
     * @param array|null $meta
     * @param array|null $links
     *
     * @return array|string
     */
    public function buildPayload(
        array $data = null,
        array $errors = null,
        array $meta = null,
        array $links = null
    ) {
        if (is_null($data) && is_null($errors) && is_null($meta)) {
            throw new \RuntimeException("A document MUST contain at least one of the following top-level members: data, errors, meta.");
        }

        if (!is_null($data) && !is_null($errors)) {
            throw new \RuntimeException("The members data and errors MUST NOT coexist in the same document.");
        }

        $payload = [];

        if (!is_null($data)) {
            $payload['data'] = $data;
        } elseif (!is_null($errors)) {
            $payload['errors'] = $errors;
        }

        if (!is_null($meta)) {
            $payload['meta'] = $meta;
        }

        if (!is_null($links)) {
            $payload['links'] = $links;
        }

        return $payload;
    }
}