<?php

/**
 * This file is part of the Utils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace Utils\EventSourcing;

use Ramsey\Uuid\UuidInterface;

/**
 * Class AbstractEvent
 *
 * @package Utils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/my-utils
 */
abstract class AbstractEvent
{
    /**
     * @var UuidInterface
     */
    protected $id;

    /**
     * @var \DateTimeInterface
     */
    protected $createdAt;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $streamId;

    /**
     * @var string
     */
    protected $streamCategory;

    public function __construct(UuidInterface $id, \DateTimeInterface $createdAt, $name, $streamId, $streamCategory)
    {
        $this->id             = $id;
        $this->createdAt      = $createdAt;
        $this->name           = $name;
        $this->streamId       = $streamId;
        $this->streamCategory = $streamCategory;
    }
}