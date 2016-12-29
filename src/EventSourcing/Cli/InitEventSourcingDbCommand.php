<?php

/**
 * This file is part of the Utils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace Utils\EventSourcing\Cli;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class InitEventSourcingDbCommand
 *
 * @package Utils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/my-utils
 */
class InitEventSourcingDbCommand extends Command
{
    protected function configure()
    {
        $this->setName('utils:init_event_sourcing_db')
             ->setDescription('Init the shared event sourcing database.')
             ->addOption('host', null, InputOption::VALUE_REQUIRED, 'DB host')
             ->addOption('db', 'd', InputOption::VALUE_REQUIRED, 'DB name')
             ->addOption('user', 'u', InputOption::VALUE_REQUIRED, 'DB username')
             ->addOption('password', 'p', InputOption::VALUE_REQUIRED, 'DB password')
             ->addOption('port', null, InputOption::VALUE_REQUIRED, 'DB port', '3306')
             ->addOption('charset', 'c', InputOption::VALUE_REQUIRED, 'DB charset', 'utf8')
             ->addOption('event_table', null, InputOption::VALUE_REQUIRED, 'Event table name')
             ->addOption('processed_event_table', null, InputOption::VALUE_REQUIRED, 'Processed event table name');
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $map = [
            'host'                  => "Please enter the host: ",
            'db'                    => "Please enter the DB name: ",
            'user'                  => "Please enter the username to connect to the DB: ",
            'password'              => "Please enter the password to connect to the DB: ",
            'port'                  => "Please enter the port to connect to the DB: ",
            'charset'               => "Please enter the charset to connect to the DB: ",
            'event_table'           => "Please enter the table name used to store the event log: ",
            'processed_event_table' => "Please enter the name of the table used to store the processed event: ",
        ];
        $h   = $this->getHelper('question');

        foreach ($map as $option => $question) {
            if ($input->getOption($option) === null) {
                $question = new Question($question);
                $answer   = $h->ask($input, $output, $question);

                if ($answer === null) {
                    $output->writeln("All options are mandatory to successfully execute the command.");

                    exit;
                }

                $input->setOption($option, $answer);
            }
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $connection = DriverManager::getConnection([
                'dbname'   => $input->getOption('db'),
                'user'     => $input->getOption('user'),
                'password' => $input->getOption('password'),
                'host'     => $input->getOption('host'),
                'port'     => $input->getOption('port'),
                'driver'   => 'pdo_mysql',
            ]);
        } catch (\Exception $e) {
            $output->writeln("Unable to init the DB connection.");
            $output->writeln($e->getMessage());

            return 1;
        }

        $platform = $connection->getDatabasePlatform();
        $schema   = new Schema();

        $eventTableName          = 'event';
        $processedEventTableName = 'processed_event';

        $this->eventTableSchema($schema, $eventTableName);
        $this->processedEventTableSchema($schema, $processedEventTableName);

        $connection->exec($schema->toSql($platform)[0]);
        $connection->exec("ALTER TABLE `$eventTableName` ADD `created_at` DATETIME(6) NOT NULL FIRST, ADD PRIMARY KEY (`created_at`, `id`)");
        $output->writeln("Created $eventTableName table.");

        $connection->exec($schema->toSql($platform)[1]);
        $connection->exec("ALTER TABLE `$processedEventTableName` ADD `created_at` DATETIME(6) NOT NULL AFTER `processed_by`, ADD INDEX `created_at` (`created_at`)");
        $output->writeln("Created $processedEventTableName table.");
    }

    /**
     * eventTableSchema
     *
     * @param Schema $schema
     * @param string $tableName
     *
     * @return Table
     */
    private function eventTableSchema(Schema $schema, $tableName)
    {
        $t = $schema->createTable($tableName);
        $t->addColumn("id", "string", ["length" => 36]);
        $t->addColumn("name", "string", ["length" => 255]);
        $t->addColumn("stream_id", "string", ["length" => 255]);
        $t->addColumn("stream_category", "string", ["length" => 255]);
        $t->addColumn("payload", "text", ["length" => MySqlPlatform::LENGTH_LIMIT_MEDIUMTEXT]);
        $t->addIndex(["name"]);
        $t->addIndex(["stream_id"]);
        $t->addIndex(["stream_category"]);

        return $t;
    }

    /**
     * processedEventTableSchema
     *
     * @param Schema $schema
     * @param string $tableName
     *
     * @return Table
     */
    private function processedEventTableSchema(Schema $schema, $tableName)
    {
        $t = $schema->createTable($tableName);
        $t->addColumn("event_id", "string", ["length" => 36]);
        $t->addColumn("processed_by", "string", ["length" => 100]);
        $t->setPrimaryKey(['event_id', 'processed_by']);

        return $t;
    }
}