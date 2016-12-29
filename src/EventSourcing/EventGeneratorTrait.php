<?php

/**
 * This file is part of the Utils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace Utils\EventSourcing;

/**
 * Trait EventGeneratorTrait
 *
 * @package Utils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/my-utils
 */
trait EventGeneratorTrait
{
    /**
     * @var AbstractEvent[]
     */
    protected $pendingEvents = [];

    protected function raise(AbstractEvent $event)
    {
        $this->pendingEvents[] = $event;
    }

    /**
     * releaseEvents.
     *
     * @return AbstractEvent[]
     */
    public function releaseEvents()
    {
        $events              = $this->pendingEvents;
        $this->pendingEvents = [];

        return $events;
    }
}