<?php

/**
 * This file is part of the Utils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace Utils\UseCase;

/**
 * Interface ValidatorInterface
 *
 * @package Utils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/my-utils
 */
interface ValidatorInterface
{
    /**
     * validate.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function validate(Request $request, Response $response);
}