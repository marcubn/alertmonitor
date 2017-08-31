<?php

namespace AlertMonitor\Service\Output;
/**
 * Created by PhpStorm.
 * User: bogdan
 * Date: 5/22/17
 * Time: 2:39 PM
 */
interface OutputInterface
{
    public function processResults($data);
}