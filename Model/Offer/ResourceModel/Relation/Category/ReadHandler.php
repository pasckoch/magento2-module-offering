<?php

namespace DnD\Offering\Model\Offer\ResourceModel\Relation\Category;

use DnD\Offering\Model\Offer\ResourceModel\Relation\AbstractHandler;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class ReadHandler
 */
class ReadHandler extends AbstractHandler
{
    /**
     * @param $entity
     * @param $arguments
     * @return object|bool
     */
    public function execute($entity, $arguments = []): object|bool
    {
        if ($entity->getId()) {
            try {
                $entity->setData('category_id', $this->resource->lookupCategoryIds((int)$entity->getId()));
            } catch (LocalizedException) {
                return false;
            }
        }
        return $entity;
    }
}
