<?php

declare(strict_types=1);


namespace App\Factory;


use GuzzleHttp\Client;

/**
 * Class abstractClient
 * @package App\Factory
 */
class AbstractClient
{

    /** @var Client */
    protected $client;

    /**
     * Create a GuzzleHttp client taking into account base uri, there are two base url to execute requests to Spotify.
     *
     * @param string $uri
     * @return Client
     */
    public function createClient(string $uri)
    {
        if (!$this->client) {
            $this->client = new Client(['base_uri' => $uri]);
        }

        return $this->client;
    }

}
