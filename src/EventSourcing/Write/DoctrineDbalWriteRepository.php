<?php

/**
 * This file is part of the Utils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace Utils\EventSourcing\Write;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Connection as ConnectionInterface;
use Zend\Hydrator\HydratorInterface;

/**
 * Class DoctrineDbalWriteRepository
 *
 * @package Utils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/my-utils
 */
class DoctrineDbalWriteRepository implements WriteRepositoryInterface
{
    /**
     * @var ConnectionInterface|Connection
     */
    private $connection;

    /**
     * @var HydratorInterface[]
     */
    private $hydratorsMap;

    /**
     * @var string
     */
    private $tableName;

    public function __construct(Connection $connection, array $hydratorsMap, $tableName)
    {
        $this->connection = $connection;
        $this->tableName  = $tableName;

        $this->setHydratorsMap($hydratorsMap);
    }

    public function add($event)
    {
        $this->addMultiple([$event]);
    }

    public function addMultiple(array $events)
    {
        foreach ($events as $event) {
            $e = $this->getHydratorFromEvent($event)->extract($event);

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

    private function setHydratorsMap(array $hydratorsMap)
    {
        foreach ($hydratorsMap as $eventClass => $hydrator) {
            if (!$hydrator instanceof HydratorInterface) {
                throw new \RuntimeException("Given Hydrator for event $eventClass not extend " . HydratorInterface::class);
            }

            $this->hydratorsMap[$eventClass] = $hydrator;
        }
    }

    /**
     * getHydratorFromEvent
     *
     * @param $event
     *
     * @return HydratorInterface
     */
    private function getHydratorFromEvent($event)
    {
        $class = get_class($event);

        if (!isset($this->hydratorsMap[$class])) {
            throw new \RuntimeException("No Hydrator found for event $class");
        }

        return $this->hydratorsMap[$class];
    }
}