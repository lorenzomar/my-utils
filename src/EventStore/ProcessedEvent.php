<?php

/**
 * This file is part of the Utils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace Utils\EventStore;

use Ramsey\Uuid\UuidInterface;

/**
 * Class ProcessedEvent
 *
 * @package Utils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/my-utils
 */
class ProcessedEvent
{
    /**
     * @var UuidInterface
     */
    private $eventId;

    /**
     * @var string
     */
    private $processedBy;

    private $startAt;

    private $endAt;

    /**
     * @var string processing|processed
     */
    private $status;

    public static function fromEvent(Event $event)
    {
        $s          = new static();
        $s->eventId = $event->id();
    }
}