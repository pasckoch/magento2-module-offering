<?php

namespace DnD\Offering\Model\Offer\ResourceModel\Relation;

use DnD\Offering\Model\Offer\ResourceModel\Offer;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;

class AbstractHandler implements ExtensionInterface
{

    /**
     * @param MetadataPool $metadataPool
     * @param Offer $resource
     */
    public function __construct(
        protected MetadataPool $metadataPool,
        protected Offer $resource
    ) {
    }

    /**
     * Perform action on relation/extension attribute
     *
     * @param object $entity
     * @param array $arguments
     * @return object|bool
     */
    public function execute($entity, $arguments = []): object|bool
    {
        return $entity;
    }
}
