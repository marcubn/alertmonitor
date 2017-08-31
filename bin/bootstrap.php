<?php
require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
use Symfony\Component\Console\Application;
use AlertMonitor\Command\RunCommand;
$application = new Application();
$application->add(new RunCommand());
$application->run();