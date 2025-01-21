<?php

namespace DnD\Offering\Model\Offer;

use DnD\Offering\Api\Data\OfferInterface;
use DnD\Offering\Model\Offer\ResourceModel\Offer;
use Exception;
use Magento\Catalog\Model\Category;
use Magento\Framework\Exception\LocalizedException;
use DnD\Offering\Model\Offer\ResourceModel\AbstractCollection;
use Magento\Store\Model\Store;

class Collection extends AbstractCollection
{

    /**
     * @return void
     * @throws LocalizedException
     */
    protected function _construct(): void
    {
        $this->_init(\DnD\Offering\Model\Offer::class, Offer::class);
        $this->_setIdFieldName($this->getResource()->getIdFieldName());
    }

    /**
     * Perform operations after collection load
     *
     * @return $this
     */
    protected function _afterLoad(): static
    {
        $this->performAfterLoad(Offer::TABLE_STORE_NAME, 'performStoreAfterLoad', 'offer_id');
        return parent::_afterLoad();
    }

    /**
     * Add filter by store
     *
     * @param int|array|Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter(Store|int|array $store, bool $withAdmin = true): static
    {
        $this->performAddStoreFilter($store, $withAdmin);
        return $this;
    }

    /**
     * Add filter by category
     *
     * @param int|array|Category $category
     * @return $this
     */
    public function addCategoryFilter(Category|int|array $category): static
    {
        $this->performAddCategoryFilter($category);
        return $this;
    }

    /**
     * Join store relation table if there is store filter
     *
     * @return void
     * @throws Exception
     */
    protected function _renderFiltersBefore(): void
    {
        $entityMetadata = $this->metadataPool->getMetadata(OfferInterface::class);
        $this->joinRelationTable(Offer::TABLE_STORE_NAME,  'store_id', $entityMetadata->getLinkField());
        $this->joinRelationTable(Offer::TABLE_CATEGORY_NAME,  'category_id', $entityMetadata->getLinkField());
        parent::_renderFiltersBefore();
    }

}
