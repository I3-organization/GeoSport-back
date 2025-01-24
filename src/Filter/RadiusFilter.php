<?php

namespace App\Filter;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Exception\InvalidParameterException;
use Doctrine\ORM\QueryBuilder;

final class RadiusFilter extends AbstractFilter
{
    private function areParametersValid(array $parameters): bool
    {
        if (!isset($parameters['latitude']) || !isset($parameters['longitude']) || !isset($parameters['radius'])) {
            return false;
        }
        if (!is_numeric($parameters['latitude']) || !is_numeric($parameters['longitude']) || !is_numeric($parameters['radius'])) {
            throw new InvalidParameterException(['latitude', 'longitude', 'radius']);
        }

        return true;
    }

    /**
     * @throws InvalidParameterException
     */
    protected function filterProperty(
        string                      $property,
                                    $value,
        QueryBuilder                $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string                      $resourceClass,
        ?Operation                  $operation = null,
        array                       $context = []
    ): void
    {
        if (!$this->isPropertyEnabled($property, $resourceClass) || !$this->isPropertyMapped($property, $resourceClass)) {
            return;
        }

        if (!$this->areParametersValid($context['filters'])) {
            return;
        }

        $latitude = $context['filters']['latitude'];
        $longitude = $context['filters']['longitude'];
        $radius = $context['filters']['radius'];

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $distanceAlias = $queryNameGenerator->generateParameterName('distance');

        $distanceExpression = '(6371 * acos(
        cos(radians(:latitude)) * cos(radians(' . $rootAlias . '.latitude)) *
        cos(radians(' . $rootAlias . '.longitude) - radians(:longitude)) +
        sin(radians(:latitude)) * sin(radians(' . $rootAlias . '.latitude))
    ))';

        $queryBuilder
            ->andWhere("$distanceExpression <= :radius")
            ->setParameter('latitude', $latitude)
            ->setParameter('longitude', $longitude)
            ->setParameter('radius', $radius);

        if (!str_contains($queryBuilder->getDQL(), "$distanceExpression AS $distanceAlias")) {
            $queryBuilder
                ->addSelect("$distanceExpression AS HIDDEN $distanceAlias")
                ->orderBy($distanceAlias, 'ASC');
        }
    }


    public function getDescription(string $resourceClass): array
    {
        return [
            'latitude' => [
                'property' => 'latitude',
                'type' => 'float',
                'required' => false,
                'swagger' => [
                    'description' => 'Latitude of the center point',
                    'name' => 'Latitude',
                    'type' => 'float',
                ],
            ],
            'longitude' => [
                'property' => 'longitude',
                'type' => 'float',
                'required' => false,
                'swagger' => [
                    'description' => 'Longitude of the center point',
                    'name' => 'Longitude',
                    'type' => 'float',
                ],
            ],
            'radius' => [
                'property' => 'radius',
                'type' => 'float',
                'required' => false,
                'swagger' => [
                    'description' => 'Radius in meters',
                    'name' => 'Radius',
                    'type' => 'float',
                ],
            ],
        ];
    }
}