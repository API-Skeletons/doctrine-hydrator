<?php

namespace ZF\Doctrine\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;
use Zend\Filter\FilterChain;
use DoctrineModule\Stdlib\Hydrator\Strategy\AbstractCollectionStrategy;
use ZF\Hal\Link\Link;

/**
 * An entity-specific hydrator
 *
 * @returns Link
 */
class EntityLink implements
    StrategyInterface
{
    protected $metadataMap;
    protected $filterKey;

    public function setMetadataMap(array $map)
    {
        $this->metadataMap = $map;

        return $this;
    }

    public function getMetadataMap()
    {
        return $this->metadataMap;
    }

    public function extract($value)
    {
        $entityMetadata = $this->getMetadataMap()[get_class($value)];

        $identifierField = $entityMetadata['entity_identifier_name'];
        $identifierFieldGetter = 'get' . $identifierField;

        $link = new Link('self');
        $link->setRoute($entityMetadata['route_name']);
        $link->setRouteParams(array($identifierField => $value->{$identifierFieldGetter}()));

        return $link;
    }

    public function hydrate($value)
    {
        // This strategy does note support hydration of collections.
    }
}