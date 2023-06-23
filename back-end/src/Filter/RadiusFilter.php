<?php

namespace App\Filter;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\PropertyInfo\Type;

final class RadiusFilter extends AbstractFilter
{

    protected function filterProperty(
        string $property, 
        $value, 
        QueryBuilder $queryBuilder, 
        QueryNameGeneratorInterface $queryNameGenerator, 
        string $resourceClass, 
        Operation $operation = null, 
        array $context = []
    ): void
    {

        // otherwise filter is applied to order and page as well
        if ($property !== 'distance') {
            return;
        }
        
        $filter = json_decode($value, true); 
        $lat = $filter['latitude'];
        $long = $filter['longitude'];
        $radius = $filter['radius'];
        
        // add distance in DQL based on filter location point
        $rootAlias = $queryBuilder->getRootAliases()[0];
        $distanceCondition = '
        :radius > (111111 * DEGREES(
                ACOS(
                    LEAST(
                        1.0, 
                        COS(RADIANS('.$rootAlias.'.latitude)) * COS(RADIANS(:lat)) * COS(RADIANS('.$rootAlias.'.longitude - :long)) + SIN(RADIANS('.$rootAlias.'.latitude)) * SIN(RADIANS(:lat))
                    )
                )             
            )
        )
        ';
        $queryBuilder
            ->andWhere($distanceCondition)
            ->setParameter('radius', $radius)
            ->setParameter('lat', $lat)
            ->setParameter('long', $long)
        ;

    }

    public function getDescription(string $resourceClass): array
    {
        $description = [];
        $description["distance"] = [
            'property' => "distance",
            'type' => Type::BUILTIN_TYPE_STRING,
            'required' => false,
            'description' => 'Filter using a point and a radius around the point',
            'openapi' => [
                'example' => '{"latitude":"48.665666","longitude":"6.139670","radius":100000}',
                'allowReserved' => false,
                'allowEmptyValue' => true,
                'explode' => false, 
            ],
        ];
        
        return $description;
    }


}