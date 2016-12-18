<?php

/**
 * This file is part of the Utils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace Utils\UseCase;

/**
 * Interface UseCaseInterface
 *
 * @package Utils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/utils
 */
interface UseCaseInterface
{
    /**
     * __invoke.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request);
}