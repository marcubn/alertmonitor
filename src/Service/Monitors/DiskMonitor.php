<?php

namespace AlertMonitor\Service\Monitors;
use AlertMonitor\Service\Monitors\MonitorInterface;
use phpseclib\Net\SSH2 as SSH2;

/**
 * Class DiskMonitor
 * @package AlertMonitor\Service\Monitors
 */
class DiskMonitor implements MonitorInterface
{
    private $name;

    private $url;

    private $user;

    private $pass;

    private $lastMessage;

    public function __construct($name, $url, $user, $pass)
    {
        $this->name = $name;
        $this->url = $url;
        $this->user = $user;
        $this->pass = $pass;
    }

    public function inThreshold()
    {
        return $this->getAvailableDiskSpace() > 10;
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
        return 'Low space on server!';
    }

    public function getBody()
    {
        return 'Available disk space '.$this->lastMessage.'%  for server '.$this->name;
    }

    private function getAvailableDiskSpace()
    {
        $ssh = new SSH2($this->url);
        if (!$ssh->login($this->user, $this->pass)) {
           return -10;
        }
        $space = trim( str_replace( PHP_EOL, ' ', $ssh->exec("df -h /home | awk '{ sum=100-$5 } IF sum == 100 { sum=100-$4 } END { print sum }'") ) );
        $this->lastMessage = $space;
        return (int)$space;
    }
}