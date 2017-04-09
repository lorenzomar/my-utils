<?php

/**
 * This file is part of the Utils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace Utils\EventStore;

use Ramsey\Uuid\UuidInterface;
use Utils\MicrosecondsDateTimeBuilder;

/**
 * Class Event
 *
 * @package Utils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/my-utils
 */
class Event
{
    use MicrosecondsDateTimeBuilder;

    /**
     * @var UuidInterface
     */
    private $uuid;

    /**
     * @var \DateTimeInterface
     */
    private $at;

    /**
     * @var int autoincrement int
     */
    private $number;

    /**
     * @var string
     */
    private $streamId;

    /**
     * @var string
     */
    private $streamCategory;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $payload;

    /**
     * @var array
     */
    private $meta;

    public function __construct(
        UuidInterface $uuid,
        $streamId,
        $streamCategory,
        $name,
        array $payload,
        array $meta = []
    ) {
        $this->uuid           = $uuid;
        $this->at             = $this->microsecondsDateTime();
        $this->streamId       = $streamId;
        $this->streamCategory = $streamCategory;
        $this->name           = $name;
        $this->payload        = $payload;
        $this->meta           = $meta;
    }

    /**
     * uuid.
     *
     * @return UuidInterface
     */
    public function uuid()
    {
        return $this->uuid;
    }

    /**
     * at.
     *
     * @return \DateTimeInterface
     */
    public function at()
    {
        return $this->at;
    }

    /**
     * streamId.
     *
     * @return string
     */
    public function streamId()
    {
        return $this->streamId;
    }

    /**
     * streamCategory.
     *
     * @return string
     */
    public function streamCategory()
    {
        return $this->streamCategory;
    }

    /**
     * name.
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * payload.
     *
     * @return array
     */
    public function payload()
    {
        return $this->payload;
    }

    /**
     * meta.
     *
     * @return array
     */
    public function meta()
    {
        return $this->meta;
    }
}