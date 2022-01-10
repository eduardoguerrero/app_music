<?php


declare(strict_types=1);

namespace App\Factory;


use GuzzleHttp\Client;

/**
 * Class ClientRequest
 * @package App\Factory
 */
class ClientRequest extends AbstractClient
{

    /** @var string */
    public $baseUri;

    /**
     * ClientRequest constructor.
     * @param array $spotifyApi
     */
    public function __construct(array $spotifyApi)
    {
        $this->baseUri = $spotifyApi['base_uri'];
    }

    /**
     * Get client to execute request in Spotify
     *
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->createClient($this->baseUri);
    }

}
