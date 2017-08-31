<?php

namespace AlertMonitor\Service\Monitors;
use AlertMonitor\Service\Monitors\MonitorInterface;

/**
 * Class HttpMonitor
 * @package AlertMonitor\Service\Monitors
 */
class HttpMonitor implements MonitorInterface
{
    private $name;

    private $url;

    private $lastMessage;

    public function __construct($name, $url)
    {
        $this->name = $name;
        $this->url = $url;
    }

    public function inThreshold()
    {
        return $this->getCurrentStatus() === 200;
    }

    public function getData()
    {
        return $this->lastMessage;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSubject()
    {
        return 'Incorect response alert!';
    }

    public function getBody()
    {
        return 'Result '.$this->lastMessage.'  for request '.$this->name;
    }

    private function getCurrentStatus()
    {
        $headers = get_headers($this->url);
        $statusCode = substr($headers[0], 9, 3 );
        $this->lastMessage = json_encode($headers);
        return (int)$statusCode;
    }
}