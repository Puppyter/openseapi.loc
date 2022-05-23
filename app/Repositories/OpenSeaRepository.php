<?php

namespace App\Repositories;

use GuzzleHttp\Client;

class OpenSeaRepository
{

    private $client;

    public function __construct()
    {
        $this->client = new Client();

    }

    public function get($owner)
    {
        $response = $this->client->request('GET', 'https://api.opensea.io/api/v1/assets?owner=' . $owner . '&order_direction=desc&limit=20&include_orders=false', [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
        return $response->getBody();
    }
}
