<?php

namespace DnD\Offering\Model\Offer\ResourceModel;

use Magento\Catalog\Model\Category;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Zend_Db_Select;

/**
 * Abstract collection of CMS pages and blocks
 */
abstract class AbstractCollection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @param EntityFactoryInterface $entityFactory
     * @param LoggerInterface $logger
     * @param FetchStrategyInterface $fetchStrategy
     * @param ManagerInterface $eventManager
     * @param StoreManagerInterface $storeManager
     * @param MetadataPool $metadataPool
     * @param AdapterInterface|null $connection
     * @param AbstractDb|null $resource
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        protected StoreManagerInterface $storeManager,
        protected MetadataPool $metadataPool,
        AdapterInterface $connection = null,
        AbstractDb $resource = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }

    /**
     * Perform operations after collection load
     *
     * @param string $tableName
     * @param string|null $linkField
     * @return void
     */
    protected function performAfterLoad(string $tableName, string $performCallbackAfterLoad, ?string $linkField): void
    {
        $linkedIds = $this->getColumnValues($linkField);
        if (count($linkedIds)) {
            $connection = $this->getConnection();
            $select = $connection->select()->from([$tableName => $this->getTable($tableName)])
                ->where($tableName . '.' . $linkField . ' IN (?)', $linkedIds);
            $result = $connection->fetchAll($select);
            if ($result) {
                $data = [];
                foreach ($result as $resultData) {
                    $data[$resultData[$linkField]][] = $resultData['store_id'];
                }

                foreach ($this as $item) {
                    call_user_func_array([$this, $performCallbackAfterLoad], [$item, $data, $linkField]);
                }
            }
        }
    }

    /**
     * @param $item
     * @param $data
     * @param $linkField
     * @return void
     * @throws NoSuchEntityException
     */
    protected function performStoreAfterLoad($item, $data, $linkField): void
    {
        $linkedId = $item->getData($linkField);
        if (!isset($data[$linkedId])) {
            return;
        }
        $storeIdKey = array_search(Store::DEFAULT_STORE_ID, $data[$linkedId], true);
        if ($storeIdKey !== false) {
            $stores = $this->storeManager->getStores(false, true);
            $storeId = current($stores)->getId();
            $storeCode = key($stores);
        } else {
            $storeId = current($data[$linkedId]);
            $storeCode = $this->storeManager->getStore($storeId)->getCode();
        }
        $item->setData('_first_store_id', $storeId);
        $item->setData('store_code', $storeCode);
        $item->setData('store_id', $data[$linkedId]);
    }

    /**
     * Add field filter to collection
     *
     * @param array|string $field
     * @param string|int|array|null $condition
     * @return $this
     */
    public function addFieldToFilter($field, $condition = null): static
    {
        if ($field === 'store_id') {
            return $this->addStoreFilter($condition, false);
        }
        if ($field === 'category_id') {
            return $this->addCategoryFilter($condition);
        }

        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * Add filter by store
     *
     * @param int|array|Store $store
     * @param bool $withAdmin
     * @return $this
     */
    abstract public function addStoreFilter(Store|int|array $store, bool $withAdmin = true): static;

    /**
     * Add filter by category
     *
     * @param Category|int|array $category
     * @return $this
     */
    abstract public function addCategoryFilter(Category|int|array $category): static;

    /**
     * Perform adding filter by store
     *
     * @param int|array|Store $store
     * @param bool $withAdmin
     * @return void
     */
    protected function performAddStoreFilter(Store|int|array $store, bool $withAdmin = true): void
    {
        if ($store instanceof Store) {
            $store = [$store->getId()];
        }

        if (!is_array($store)) {
            $store = [$store];
        }

        if ($withAdmin) {
            $store[] = Store::DEFAULT_STORE_ID;
        }

        $this->addFilter('store_id', ['in' => $store], 'public');
    }

    /**
     * Perform adding filter by store
     *
     * @param Category|int|array $category
     * @return void
     */
    protected function performAddCategoryFilter(Category|int|array $category): void
    {
        if ($category instanceof Category) {
            $category = [$category->getId()];
        }

        if (!is_array($category)) {
            $category = [$category];
        }

        $this->addFilter('category_id', ['in' => $category], 'public');
    }

    /**
     * Join relation table if there is field filter
     *
     * @param string $tableName
     * @param string $field
     * @param string|null $linkField
     * @return void
     */
    protected function joinRelationTable(string $tableName, string $field, ?string $linkField): void
    {
        if ($this->getFilter($field)) {
            $this->getSelect()->join(
                [$tableName => $this->getTable($tableName)],
                'main_table.' . $linkField . ' = ' . $tableName . '.' . $linkField,
                []
            )->group(
                'main_table.' . $linkField
            );
        }
    }

    /**
     * @return Select
     */
    public function getSelectCountSql(): Select
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Zend_Db_Select::GROUP);

        return $countSelect;
    }

    /**
     * Returns pairs identifier - title for unique identifiers
     * and pairs identifier|entity_id - title for non-unique after first
     *
     * @return array
     */
    public function toOptionIdArray(): array
    {
        $res = [];
        $existingIdentifiers = [];
        foreach ($this as $item) {
            $identifier = $item->getData('identifier');

            $data['value'] = $identifier;
            $data['label'] = $item->getData('title');

            if (in_array($identifier, $existingIdentifiers)) {
                $data['value'] .= '|' . $item->getData($this->getIdFieldName());
            } else {
                $existingIdentifiers[] = $identifier;
            }

            $res[] = $data;
        }

        return $res;
    }
}
