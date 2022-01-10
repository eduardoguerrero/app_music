<?php

declare(strict_types=1);

namespace App\Factory;

/**
 * Class ClientFactory
 * @package App\Factory
 */
class ClientFactory
{
    public const CLIENT_TYPE_AUTH = 'auth';
    public const CLIENT_TYPE_API = 'api';

    /** @var mixed */
    protected $client;

    /** @var array */
    protected $spotifyApi;

    /**
     * ClientFactory constructor.
     * @param array $spotifyApi
     */
    public function __construct(array $spotifyApi)
    {
        $this->spotifyApi = $spotifyApi;
    }

    /**
     * Create a client object
     *
     * @param string $type
     * @return ClientAuth|ClientRequest|mixed
     */
    public function make(string $type)
    {
        switch ($type) {
            case self::CLIENT_TYPE_AUTH:
                $this->client = new ClientAuth($this->spotifyApi);
                break;
            case  self::CLIENT_TYPE_API:
                $this->client = new ClientRequest($this->spotifyApi);
                break;
        }

        return $this->client;
    }

}
