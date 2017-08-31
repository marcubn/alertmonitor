<?php

namespace AlertMonitor\Service\Output;


class SmsOutput implements OutputInterface
{
    private $country;

    private $from;

    private $to;

    private $url;

    private $key;

    const SUBJECT = "Something bad happened";

    public function __construct($country, $from, $to, $url, $key)
    {
        $this->country = $country;
        $this->from = $from;
        $this->to = $to;
        $this->url = $url;
        $this->key = $key;
    }

    public function processResults($data)
    {
        $textToSend = "";
        foreach ($data as $dat) {
            $textToSend .= $dat."\n";
        }
        $unique = 'promo_marvel_alert_'.time();
        foreach ($this->to as $to) {
            $to_send = array(
                "jsonrpc" => "2.0",
                "method" => "sms.sendSms",
                "id" => "'.$unique.'",
                "token" => "'.$this->key.'",
                "delayable" => false,
                "platformGroupId" => 1,
                "params" => array(
                    "data" => array(
                        "type" => "custom",
                        "requestId" => "'.$unique.'",
                        "to" => $to,
                        "countryId" => $this->country,
                        "from" => "'.$this->from.'",
                        "text" => $textToSend
                    )
                )
            );

            $post_data = json_encode($to_send);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json')
            );
            curl_exec($ch);
            curl_close($ch);
        }
    }
}