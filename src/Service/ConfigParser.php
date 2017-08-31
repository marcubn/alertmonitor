<?php

namespace AlertMonitor\Service;


class ConfigParser
{
    private $config = [];

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function getInterval()
    {
        return $this->config['config']['interval'];
    }

    public function getThreshold()
    {
        return $this->config['config']['threshold'];
    }

    public function getTimes()
    {
        return $this->config['config']['times'];
    }

    public function getCountry()
    {
        return $this->config['config']['country'];
    }

    public function getName($details)
    {
        return $details['details']['name'];
    }

    public function getHostname($details)
    {
        return $this->config['common'][$details['details']['connection']]['hostname'];
    }

    public function getUsername($details)
    {
        return $this->config['common'][$details['details']['connection']]['username'];
    }

    public function getPassword($details)
    {
        return $this->config['common'][$details['details']['connection']]['password'];
    }

    public function getVhost($details)
    {
        return $this->config['common'][$details['details']['connection']]['vhost'];
    }

    public function getType($details)
    {
        return $details['type'];
    }

    public function getConnection($details)
    {
        return $details['details']['connection'];
    }

    public function getCommon()
    {
        return $this->config['common'];
    }

    public function getMonitor()
    {
        return $this->config['monitor'];
    }

    public function getMailHost()
    {
        return $this->config['mail']['mailer_host'];
    }

    public function getMailPort()
    {
        return $this->config['mail']['mailer_port'];
    }

    public function getMailUser()
    {
        return $this->config['mail']['mailer_user'];
    }

    public function getMailPassword()
    {
        return $this->config['mail']['mailer_password'];
    }

    public function getMailFrom()
    {
        return $this->config['mail']['mail_from'];
    }

    public function getMailTo()
    {
        return $this->config['mail']['mail_to'];
    }

    public function getSmsFrom()
    {
        return $this->config['sms']['sms_from'];
    }

    public function getSmsTo()
    {
        return $this->config['sms']['sms_to'];
    }

    public function getSmsUrl()
    {
        return $this->config['sms']['sms_url'];
    }

    public function getSmsKey()
    {
        return $this->config['sms']['sms_key'];
    }

    public function getMonitors()
    {
        $monitors = array();
        foreach ($this->config['monitor'] as $name=>$monitor) {
            $temp = array();
            $temp['name'] = $name;
            $temp['type'] = $monitor['type'];
            $temp['threshold'] = $this->config['config']['threshold'];
            foreach ($monitor['details'] as $key=>$value) {
                if (substr($value, 0, 2) == '{{' && substr($value, -2) == '}}' && $this->config['common'][trim($value, '{{}}')]) {
                    $temp['details'][$key] = $this->config['common'][trim($value, '{{}}')];
                } else {
                    $temp['details'][$key] = $value;
                }
            }
            $monitors[] = $temp;
        }
        return $monitors;
    }

}
