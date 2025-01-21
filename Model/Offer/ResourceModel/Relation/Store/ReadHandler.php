<?php

namespace PascKoch\Offering\Model\Offer\ResourceModel\Relation\Store;

use PascKoch\Offering\Model\Offer\ResourceModel\Relation\AbstractHandler;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class ReadHandler
 */
class ReadHandler extends AbstractHandler
{
    /**
     * @param $entity
     * @param $arguments
     * @return bool|object
     */
    public function execute($entity, $arguments = []): object|bool
    {
        if ($entity->getId()) {
            try {
                $entity->setData('store_id', $this->resource->lookupStoreIds((int)$entity->getId()));
            } catch (LocalizedException) {
                return false;
            }
        }
        return $entity;
    }
}
