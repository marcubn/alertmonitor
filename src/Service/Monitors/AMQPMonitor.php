<?php

namespace AlertMonitor\Service\Monitors;
use AlertMonitor\Service\Monitors\MonitorInterface;

/**
 * Class AMQPMonitor
 * @package AlertMonitor\Service\Monitors
 */
class AMQPMonitor implements MonitorInterface
{
    private $name;

    private $url;

    private $credentials;

    private $threshold;

    private $lastCount;

    public function __construct($name, $url, $user, $pass, $threshold)
    {
        $this->name = $name;
        $this->url = $url;
        $this->credentials = $user . ":" . $pass;
        $this->threshold = $threshold;
    }

    public function inThreshold()
    {
        return $this->getCurrentCount() < $this->threshold;
    }

    public function getData()
    {
        return $this->lastCount;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSubject()
    {
        return 'Queue count alert!';
    }

    public function getBody()
    {
        return date('Y-m-d h:i:s').': There are '.$this->lastCount.' messages in queue '.$this->name;
    }

    private function getCurrentCount()
    {
        $messages = 0;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_USERPWD, $this->credentials);
        $content = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($content, true);
        if (isset($data['messages'])) {
            $messages = $data['messages'];
        }
        $this->lastCount = $messages;
        return $messages;
    }

}