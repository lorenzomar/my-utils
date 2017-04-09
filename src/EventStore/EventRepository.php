<?php

/**
 * This file is part of the Utils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace Utils\EventStore;

use Doctrine\DBAL\Driver\Connection;

/**
 * Class EventRepository
 *
 * @package Utils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/my-utils
 */
class EventRepository
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    private $tableName;

    public function __construct(Connection $connection, $tableName)
    {
        $this->connection = $connection;
        $this->tableName  = $tableName;
    }

    /**
     * add.
     * Aggiungi 1 o più eventi.
     *
     * @TODO ottimizzare la query
     *
     * @param Event|Event[] $event
     */
    public function add($event)
    {
        $events = is_array($event) ? $event : [$event];

        foreach ($events as $event) {
            $e = $this->extract($event);

            $this->connection->createQueryBuilder()
                             ->insert($this->tableName)
                             ->values([
                                 'created_at'      => ':created_at',
                                 'id'              => ':id',
                                 'name'            => ':name',
                                 'stream_id'       => ':stream_id',
                                 'stream_category' => ':stream_category',
                                 'payload'         => ':payload',
                             ])
                             ->setParameters($e)
                             ->execute();
        }
    }

    private function extract(Event $event)
    {
        return [
            'id'             => (string)$event->id(),
            'at'             => $event->at()->format('Y-m-d H:i:s.u'),
            'streamId'       => $event->streamId(),
            'streamCategory' => $event->streamCategory(),
            'name'           => $event->name(),
            'payload'        => $event->payload(),
            'meta'           => $event->meta(),
        ];
    }
}