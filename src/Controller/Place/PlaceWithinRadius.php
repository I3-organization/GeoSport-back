<?php

namespace App\Controller\Place;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Exception\InvalidParameterException;
use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[ApiResource(operations: [
    new Get()
])]
class PlaceWithinRadius extends AbstractController
{

    public function __construct(private PlaceRepository $placeRepository)
    {
    }

    public function __invoke(Request $request)
    {
        $latitude = $request->query->get('latitude');
        $longitude = $request->query->get('longitude');
        $radius = $request->query->get('radius');

        if (!$latitude || !$longitude || !$radius) {
            return $this->placeRepository->findAll();
        }

        if (!$longitude || !$latitude || !$radius) {
            throw new InvalidParameterException(['longitude', 'latitude', 'radius']);
        }

        $result = $this->placeRepository->findWithinRadius($latitude, $longitude, $radius);
        return $this->json($result);
    }
}