<?php

declare(strict_types=1);

namespace App\Factory;


use GuzzleHttp\Client;

/**
 * Class ClientAuth
 * @package App\Factory
 */
class ClientAuth extends AbstractClient
{

    /** @var string */
    public $baseUriAuth;

    /**
     * ClientAuth constructor.
     * @param array $spotifyApi
     */
    public function __construct(array $spotifyApi)
    {
        $this->baseUriAuth = $spotifyApi['base_uri_auth'];
    }

    /**
     * Get client to authenticate in Spotify
     *
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->createClient($this->baseUriAuth);
    }

}
