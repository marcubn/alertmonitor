<?php

namespace AlertMonitor\Service;

/**
 * Class QueueService
 * @package AlertMonitor\Service
 */
class QueueService
{
    private $outputServices = array();

    /** @var $configValues null  */
    private $configValues = null;

    /** @var $monitors array  */
    private $monitors = array();

    private $previous = array();

    private $interval;

    private $times;

    /**
     * QueueService constructor.
     * @param $configContent
     */
    public function __construct($configContent, $interval, $times)
    {
        $this->configValues = $configContent;
        $this->interval = $interval;
        $this->times = $times;
    }

    public function addOutputService ($output)
    {
        $this->outputServices[] = $output;
    }

    public function addMonitor ($monitor)
    {
        $this->monitors[] = $monitor;
    }

    public function getMonitors()
    {
        return $this->monitors;
    }

    protected function getOutputServices()
    {
        return $this->outputServices;
    }

    public function monitor()
    {
        $alerts = array();
        foreach ($this->monitors as $key => $monitor) {
            if ($monitor->inThreshold()) {
                $this->previous[$monitor->getName()] = [];
                continue;
            }
            $this->previous[$monitor->getName()][] = $monitor->getData();
            if (isset($this->previous[$monitor->getName()][$this->times - 1])) {
                $this->previous[$monitor->getName()] = [];
                $alerts[] = $monitor->getBody();
            }
        }
        if (!empty($alerts)) {
            foreach ($this->getOutputServices() as $output) {
                $output->processResults($alerts);
            }
        }

        sleep($this->interval*60);
        $this->monitor();
    }
}