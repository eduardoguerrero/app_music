<?php

declare(strict_types=1);

namespace App\Tests\Factory;


use App\Factory\ClientAuth;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

/**
 * Class ClientAuthTest
 * @package App\Tests\Factory
 */
class ClientAuthTest extends TestCase
{

    /** @var ClientAuth */
    protected $clientAuth;

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        $this->clientAuth = new ClientAuth(['base_uri_auth' => 'https://accounts.spotify.com']);
    }

    /**
     * Run after each test
     * @return void
     */
    protected function tearDown(): void
    {
        $this->clientAuth = null;
    }

    public function testInstanceClientAuth(): void
    {
        $client = $this->clientAuth->getClient();
        $this->assertInstanceOf(Client::class, $client);
    }

}
