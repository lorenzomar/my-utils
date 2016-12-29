<?php

/**
 * This file is part of the Utils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace Utils\EventSourcing\Read;

use Ramsey\Uuid\UuidInterface;

/**
 * Interface RepositoryInterface
 *
 * @package Utils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/my-utils
 */
interface RepositoryInterface
{
    /**
     * get
     *
     * @param UuidInterface $id
     *
     * @return null|ReadEvent
     */
    public function get(UuidInterface $id);

    /**
     * findAll.
     *
     * @param \DateTimeInterface|null $since
     * @param null|string             $streamId
     * @param null|string             $streamCategory
     *
     * @return AbstractEvent[]
     */
    public function findAll(\DateTimeInterface $since = null, $streamId = null, $streamCategory = null);
}