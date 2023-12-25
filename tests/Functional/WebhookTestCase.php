<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WebhookTestCase extends WebTestCase
{
    public function test(){
        $client = static::createClient();

        $client->request(
            'POST',
            '/webhook/' . 2836 . '/telegram/',
            [],
            [],
            [],
            '{
              "update_id": 321408154,
              "message": {
                "message_id": 3,
                "from": {
                  "id": 873817360,
                  "is_bot": false,
                  "first_name": "Sega",
                  "username": "sega_kgd",
                  "language_code": "ru",
                  "is_premium": true
                },
                "chat": {
                  "id": 873817360,
                  "first_name": "Sega",
                  "username": "sega_kgd",
                  "type": "private"
                },
                "date": 1703359492,
                "text": "Что-то"
              }
            }'
        );

        dd($client->getResponse()->getContent(), $client->getResponse()->getStatusCode());
    }
}
