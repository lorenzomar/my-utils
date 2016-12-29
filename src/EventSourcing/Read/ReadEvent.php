<?php

/**
 * This file is part of the Utils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace Utils\EventSourcing\Read;

use Utils\EventSourcing\AbstractEvent;

/**
 * Class ReadEvent
 *
 * @package Utils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/my-utils
 */
class ReadEvent extends AbstractEvent
{
    /**
     * @var array
     */
    protected $payload;

    public function __get($name)
    {
        // TODO: Implement __get() method.
    }
}