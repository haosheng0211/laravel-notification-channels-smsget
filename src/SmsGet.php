<?php

namespace NotificationChannels\SmsGet;

use GuzzleHttp\Client;

class SmsGet
{
    public function __construct(
        protected Client $client,
        protected string $username,
        protected string $password,
    ) {
    }

    public function send(string $phone, string $content, array $options = []): string
    {
        $params = array_merge([
            'username' => $this->username,
            'password' => $this->password,
            'method'   => '1',
            'sms_msg'  => urlencode($content),
            'phone'    => $phone,
        ], $options);

        $response = $this->client->get('http://sms-get.com/api_send.php', [
            'query' => $params,
        ]);

        return $response->getBody()->getContents();
    }
}
