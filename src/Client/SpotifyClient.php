<?php

declare(strict_types=1);

namespace App\Client;


use App\Exception\ClientGetTokenException;
use App\Factory\ClientFactory;
use App\Http\SpotifyApiClient;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

/**
 * Class SpotifyClient
 * @package App\Client
 */
class SpotifyClient
{

    /** @var SpotifyApiClient */
    protected $spotifyApiClient;

    protected $session;

    /** @var string */
    protected $clientId;

    /** @var string */
    protected $clientSecret;

    public const ATTEMPTS = 2;

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
     * @return array|ResponseInterface
     * @throws ClientGetTokenException
     * @throws GuzzleException
     */
    public function getAlbumsByBandName(string $bandName)
    {
        $response = [];
        $attempts = 0;
        do {
            try {
                $client = $this->spotifyApiClient->setClient(ClientFactory::CLIENT_TYPE_API);
                $token = $this->getSessionToken();
                $response = $client->request('GET', sprintf('search?q=artist:%s&type=album&market=ES&limit=10&offset=5', $bandName), [
                    'authorization' => ['token' => $token]
                ]);
                $attempts++;
            } catch (\Exception $e) {
                $attempts++;
                if ($e->getCode() === Response::HTTP_UNAUTHORIZED) {
                    $this->generateToken();
                }
            }
            break;
        } while ($attempts < self::ATTEMPTS);

        return $this->toArray($response);
    }

    /**
     * @param string $token
     */
    public function setSessionToken(string $token)
    {
        $session = $this->createSession();
        $session->set('spotify_access_token', $token);
    }

    /**
     * @return string|null
     */
    public function getSessionToken(): ?string
    {
        $session = $this->createSession();
        if (!$session->has('spotify_access_token')) {
            $session->set('spotify_access_token', 'spotify_access_token');
        }

        return $session->get('spotify_access_token');
    }

    /**
     * @return Session
     */
    public function createSession(): Session
    {
        $this->session = new Session(new NativeSessionStorage(), new AttributeBag());
        if (empty($this->session)) {
            $this->session->start();
        }

        return $this->session;
    }

    /**
     * Generate token
     *
     * @return mixed
     * @throws ClientGetTokenException
     * @throws GuzzleException
     */
    public function generateToken()
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
        $accessToken = $this->toArray($response)['access_token'];
        $this->setSessionToken($accessToken);

        return $accessToken;
    }

    /**
     * Parse response to array
     *
     * @param $response
     * @return array
     */
    public function toArray($response): array
    {
        if (is_array($response)) {
            return $response;
        }

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
