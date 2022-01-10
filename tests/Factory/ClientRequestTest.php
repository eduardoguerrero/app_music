<?php

declare(strict_types=1);

namespace App\Tests\Factory;


use App\Factory\ClientRequest;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

/**
 * Class ClientRequestTest
 * @package App\Tests\Factory
 */
class ClientRequestTest extends TestCase
{

    /** @var ClientRequest */
    protected $clientRequest;

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        $this->clientRequest = new ClientRequest(['base_uri' => 'https://api.spotify.com/v1/']);
    }

    /**
     * Run after each test
     * @return void
     */
    protected function tearDown(): void
    {
        $this->clientRequest = null;
    }

    public function testInstanceClientRequest(): void
    {
        $client = $this->clientRequest->getClient();
        $this->assertInstanceOf(Client::class, $client);
    }

}
