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
    protected $created_at;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $stream_id;

    /**
     * @var string
     */
    protected $stream_category;
}