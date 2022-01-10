<?php

declare(strict_types=1);


namespace App\Factory;


use GuzzleHttp\Client;

/**
 * Class AbstractClient
 * @package App\Factory
 */
class AbstractClient
{

    /** @var Client */
    protected $client;

    /**
     * Create a GuzzleHttp client, there are two base url (create token and execute requests to API)
     *
     * @param string $uri
     * @return Client
     */
    public function createClient(string $uri)
    {
        if (!$this->client) {
            $this->client = new Client([
                'base_uri' => $uri,
                'request.options' => [
                    'exceptions' => false,
                ]
            ]);
        }

        return $this->client;
    }

}
