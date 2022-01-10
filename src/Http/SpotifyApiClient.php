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

    /**
     * SpotifyApiClient constructor.
     * @param array $spotifyApi
     */
    public function __construct(array $spotifyApi)
    {
        $this->makeClient = new ClientFactory($spotifyApi);
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
     * send an HTTP request.
     *
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function request(string $method, string $uri, array $options = [])
    {
        $dataOptions = [];
        if (array_key_exists('formParams', $options)) {
            $dataOptions['form_params'] = $options['formParams'];
            $dataOptions['headers'] = ['Content-Type' => 'application/x-www-form-urlencoded'];
        }

        if (array_key_exists('authorization', $options)) {
            $dataOptions['headers'] = [
                'Authorization' => 'Bearer ' . $options['authorization']['token'],
                'Accept' => 'application/json',
            ];
        }

        $client = $this->getClient();

        return $client->request($method, $uri, $dataOptions);
    }

}
