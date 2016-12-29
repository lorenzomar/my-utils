<?php

/**
 * This file is part of the Utils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace Utils\EventSourcing\ProcessedEvent;

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

    /**
     * @var \DateTimeInterface
     */
    private $createdAt;
}