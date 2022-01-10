<?php

declare(strict_types=1);

namespace App\Http;


use App\Factory\ClientFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class SpotifyApiClient
 * @package App\Http
 */
class SpotifyApiClient
{

    /** @var mixed */
    protected $client;

    /** @var mixed */
    protected $makeClient;

    /** @var string */
    public $baseUri;

    /** @var string */
    public $baseUriAuth;

    /** @var string */
    public $clientId;

    /** @var string */
    public $clientSecret;

    /**
     * SpotifyApiClient constructor.
     * @param array $spotifyApi
     */
    public function __construct(array $spotifyApi)
    {
        $this->makeClient = new ClientFactory($spotifyApi);
        $this->baseUri = $spotifyApi['base_uri'];
        $this->baseUriAuth = $spotifyApi['base_uri_auth'];
        $this->clientId = $spotifyApi['client_id'];
        $this->clientSecret = $spotifyApi['client_secret'];
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setClient(string $type)
    {
        $this->client = $this->makeClient->make($type);

        return $this;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client->getClient();
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function request(string $method, string $uri, array $options = [])
    {
        if (array_key_exists('formParams', $options)) {
            $data['form_params'] = $options['formParams'];
            $data['headers'] = ['Content-Type' => 'application/x-www-form-urlencoded'];
        }

        if (array_key_exists('authorization', $options)) {
            $data['headers'] = [
                'Authorization' => 'Bearer ' . $options['authorization']['token'],
                'Accept' => 'application/json',
            ];
        }

        $client = $this->getClient();

        return $client->request($method, $uri, $data);
    }

}
