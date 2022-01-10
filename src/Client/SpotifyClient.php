<?php

declare(strict_types=1);

namespace App\Client;


use App\Exception\ClientGetTokenException;
use App\Factory\ClientFactory;
use App\Http\SpotifyApiClient;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SpotifyClient
 * @package App\Client
 */
class SpotifyClient
{

    /** @var SpotifyApiClient */
    protected $spotifyApiClient;

    /** @var string */
    public $clientId;

    /** @var string */
    public $clientSecret;

    /**
     * SpotifyClient constructor.
     * @param SpotifyApiClient $spotifyApiClient
     * @param array $spotifyApi
     */
    public function __construct(SpotifyApiClient $spotifyApiClient, array $spotifyApi)
    {
        $this->spotifyApiClient = $spotifyApiClient;
        $this->clientId = $spotifyApi['client_id'];
        $this->clientSecret = $spotifyApi['client_secret'];
    }

    /**
     * @param string $bandName
     * @return array
     * @throws GuzzleException
     */
    public function getAlbumsByBandName(string $bandName)
    {
        $token = 'BQDKNR1XOj5PYH6uWvWgKwQdpbwYwalRJkSz6egr3wROIhRjyVMg7X88QAvITfbtv86wzmuyC9GkoFFF0D0';
        $client = $this->spotifyApiClient->setClient(ClientFactory::CLIENT_TYPE_API);
        $data['authorization'] = [
            'token' => $token,
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ]
        ];
        $response = $client->request('GET', sprintf('search?q=artist:%s&type=album&market=ES&limit=10&offset=5', $bandName), $data);

        return $this->toArray($response);
    }

    /**
     * Get token
     *
     * @return mixed
     * @throws ClientGetTokenException
     * @throws GuzzleException
     */
    public function getToken()
    {
        $client = $this->spotifyApiClient->setClient(ClientFactory::CLIENT_TYPE_AUTH);
        $data['formParams'] = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'client_credentials'
        ];
        $response = $client->request('POST', 'api/token', $data);
        if (!$this->isValidResponse($response, Response::HTTP_OK)) {
            throw new ClientGetTokenException(sprintf('There is an error: %s', $response->getBody()));
        }

        return $this->toArray($response)['access_token'];
    }

    /**
     * Parse response to array
     *
     * @param ResponseInterface $response
     * @return array
     */
    public function toArray(ResponseInterface $response): array
    {
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Check if it is a valid response taking into account status code
     *
     * @param ResponseInterface $response
     * @param int $expectedStatus
     * @return bool
     */
    public function isValidResponse(ResponseInterface $response, int $expectedStatus): bool
    {
        return $response->getStatusCode() === $expectedStatus;
    }
}
