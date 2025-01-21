<?php

namespace PascKoch\Offering\Model\Offer\ResourceModel\Relation\Store;

use PascKoch\Offering\Api\Data\OfferInterface;
use PascKoch\Offering\Model\Offer\ResourceModel\Relation\AbstractHandler;
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

            $oldStores = $this->resource->lookupStoreIds((int)$entity->getId());
            $newStores = (array)$entity->getStores();
            if (empty($newStores)) {
                $newStores = (array)$entity->getStoreId();
            }
            $table = $this->resource->getTable('pasckoch_offer_store');

            $delete = array_diff($oldStores, $newStores);
            if ($delete) {
                $where = [
                    $linkField . ' = ?' => (int)$entity->getData($linkField),
                    'store_id IN (?)' => $delete,
                ];
                $connection->delete($table, $where);
            }

            $insert = array_diff($newStores, $oldStores);
            if ($insert) {
                $data = [];
                foreach ($insert as $storeId) {
                    $data[] = [
                        $linkField => (int)$entity->getData($linkField),
                        'store_id' => (int)$storeId
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
