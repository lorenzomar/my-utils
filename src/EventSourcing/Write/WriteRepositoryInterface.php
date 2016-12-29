<?php

/**
 * This file is part of the Utils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace Utils\EventSourcing\Write;

use Utils\EventSourcing\AbstractEvent;

/**
 * Interface WriteRepositoryInterface
 *
 * @package Utils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/my-utils
 */
interface WriteRepositoryInterface
{
    /**
     * add.
     *
     * @param AbstractEvent $event
     */
    public function add($event);

    /**
     * addMultiple.
     *
     * @param AbstractEvent[] $events
     */
    public function addMultiple(array $events);
}