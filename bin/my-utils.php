<?php

/**
 * This file is part of the Utils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../vendor/autoload.php';

$application = new \Symfony\Component\Console\Application();
$application->add(new \Utils\EventSourcing\Cli\InitEventSourcingDbCommand());
$application->run();