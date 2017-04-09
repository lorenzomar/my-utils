<?php

/**
 * This file is part of the Utils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace Utils\EventStore;

use Doctrine\DBAL\Driver\Connection;

/**
 * Class ProcessedEventRepository
 *
 * @package Utils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/my-utils
 */
class ProcessedEventRepository
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
     * hasAlreadyBeenProcessed.
     *
     * @param Event  $event
     * @param string $processedBy
     *
     * @return bool
     */
    public function hasAlreadyBeenProcessed(Event $event, $processedBy)
    {

    }

    /**
     * startProcessing
     *
     * @param Event  $event
     * @param string $processedBy
     */
    public function startProcessing(Event $event, $processedBy)
    {

    }
}