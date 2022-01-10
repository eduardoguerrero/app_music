<?php

declare(strict_types=1);

namespace App\Controller;


use App\Service\SpotifyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class Spotify
 * @package App\Controller
 */
class Spotify extends AbstractController
{

    /** @var SpotifyService */
    protected $spotifyService;

    /**
     * Spotify constructor.
     * @param SpotifyService $spotifyService
     */
    public function __construct(SpotifyService $spotifyService)
    {
        $this->spotifyService = $spotifyService;
    }

    /**
     * @param string $bandName
     * @return JsonResponse
     */
    public function getAlbumsByBandName(string $bandName): JsonResponse
    {
        $discographyList = $this->spotifyService->getAlbumsByBandName($bandName);

        return $this->json($discographyList);
    }

}
