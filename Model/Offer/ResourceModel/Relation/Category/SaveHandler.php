<?php

namespace DnD\Offering\Model\Offer\ResourceModel\Relation\Category;

use DnD\Offering\Api\Data\OfferInterface;
use DnD\Offering\Model\Offer\ResourceModel\Relation\AbstractHandler;
use Exception;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class SaveHandler
 */
class SaveHandler extends AbstractHandler
{
    /**
     * @param $entity
     * @param $arguments
     * @return object|bool
     */
    public function execute($entity, $arguments = []): object|bool
    {
        try {
            $entityMetadata = $this->metadataPool->getMetadata(OfferInterface::class);
            $linkField = $entityMetadata->getLinkField();

            $connection = $entityMetadata->getEntityConnection();

            $oldCategories = $this->resource->lookupCategoryIds((int)$entity->getId());
            $newCategories = (array)$entity->getCategories();

            if (!count($newCategories)) {
                $newCategories = (array)$entity->getCategoryId();
            }
            $table = $this->resource->getTable('dnd_offer_category');

            $delete = array_diff($oldCategories, $newCategories);
            if ($delete) {
                $where = [
                    $linkField . ' = ?' => (int)$entity->getData($linkField),
                    'category_id IN (?)' => $delete,
                ];
                $connection->delete($table, $where);
            }

            $insert = array_diff($newCategories, $oldCategories);
            if ($insert) {
                $data = [];
                foreach ($insert as $categoryId) {
                    $data[] = [
                        $linkField => (int)$entity->getData($linkField),
                        'category_id' => (int)$categoryId
                    ];
                }
                $connection->insertMultiple($table, $data);
            }
        } catch (Exception|LocalizedException) {
            return false;
        }

        return $entity;
    }
}
