<?php


namespace AlertMonitor\Service\Output;

use Symfony\Component\Console\Output\OutputInterface as ConsoleOut;

class ConsoleOutput implements OutputInterface
{
    /** @var  ConsoleOut $consoleOutput */
    private $consoleOutput;

    public function __construct($consoleOutput)
    {
        $this->consoleOutput = $consoleOutput;
    }

    public function processResults($data)
    {
        $this->consoleOutput->writeln(implode("\n", $data));
    }
}