<?php

declare(strict_types=1);

namespace App\Service;


use App\Client\SpotifyClient;

/**
 * Class SpotifyService
 * @package App\Service
 */
class SpotifyService
{

    /** @var  SpotifyClient */
    protected $spotifyClient;

    /**
     * SpotifyService constructor.
     * @param SpotifyClient $spotifyClient
     */
    public function __construct(SpotifyClient $spotifyClient)
    {
        $this->spotifyClient = $spotifyClient;
    }

    /**
     * @param string $bandName
     * @return string
     */
    public function getAlbumsByBandName(string $bandName)
    {
        return $this->spotifyClient->getAlbumsByBandName($bandName);
    }

}
