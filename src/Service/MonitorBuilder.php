<?php

namespace AlertMonitor\Service;


use AlertMonitor\Service\Monitors\AMQPMonitor;
use AlertMonitor\Service\Monitors\DiskMonitor;
use AlertMonitor\Service\Monitors\HttpMonitor;

class MonitorBuilder
{
    const AMQP_TYPE = 'amqp-http-queue';
    const HTTP_TYPE = 'http-request';
    const DISK_TYPE = 'disk-request';

    public function buildMonitor($data)
    {
        $details = $data['details'];
        switch ($data['type']) {
            case self::AMQP_TYPE:
                $url = $this->constructUrl($details['connection']['hostname'], $details['connection']['vhost'], $details['name']);
                $monitor = new AMQPMonitor($data['name'], $url, $details['connection']['username'], $details['connection']['password'], $data['threshold']);
                break;
            case self::HTTP_TYPE:
                $monitor = new HttpMonitor($data['name'], $details['url']);
                break;
            case self::DISK_TYPE:
                $monitor = new DiskMonitor($data['name'], $details['connection']['hostname'], $details['connection']['username'], $details['connection']['password']);
                break;
        }
        return $monitor;
    }

    private function constructUrl($hostname, $vhost, $name)
    {
        $url = "http://$hostname:15672/api/queues/" . urlencode($vhost) . "/{$name}";
        return $url;
    }

}