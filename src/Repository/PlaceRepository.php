<?php

namespace App\Repository;

use App\Entity\Place;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Place>
 */
class PlaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Place::class);
    }

    public function findWithinRadius(float $latitude, float $longitude, float $radius): array
    {
        $qb = $this->createQueryBuilder('p');

        $qb
            ->select('p')
            ->addSelect(
                sprintf(
                    '(
                6371 * acos(
                    cos(radians(:latitude)) * cos(radians(p.latitude)) * cos(radians(p.longitude) - radians(:longitude)) +
                    sin(radians(:latitude)) * sin(radians(p.latitude))
                )
            ) AS distance'
                )
            )
            ->having('distance <= :radius')
            ->setParameter('latitude', $latitude)
            ->setParameter('longitude', $longitude)
            ->setParameter('radius', $radius);

        return $qb->getQuery()->getResult();
    }
}
