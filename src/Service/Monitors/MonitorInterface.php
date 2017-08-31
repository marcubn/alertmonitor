<?php

namespace AlertMonitor\Service\Monitors;

/**
 * Interface MonitorInterface
 * @package AlertMonitor\Service\Monitors
 */
interface MonitorInterface
{
    public function inThreshold();
    public function getData();
    public function getName();
    public function getSubject();
    public function getBody();
}