<?php

namespace DnD\Offering\Model\Offer\ResourceModel;

use DnD\Offering\Api\Data\OfferInterface;
use Exception;
use Magento\Framework\DB\Select;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\EntityManager\EntityMetadata;
use Magento\Framework\EntityManager\EntityMetadataInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\Store;
use Zend_Db_Select;

class Offer extends AbstractDb
{

    public const TABLE_NAME = 'dnd_offer';

    public const TABLE_STORE_NAME = 'dnd_offer_store';

    public const TABLE_CATEGORY_NAME = 'dnd_offer_category';

    /** @var MetadataPool */
    protected MetadataPool $metadataPool;

    /** @var EntityManager  */
    protected EntityManager $entityManager;

    public function __construct(
        Context $context,
        MetadataPool $metadataPool,
        EntityManager $entityManager,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->metadataPool = $metadataPool;
        $this->entityManager = $entityManager;
    }

    /**
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(self::TABLE_NAME, OfferInterface::OFFER_ID);
    }

    /**
     * Get page identifier
     *
     * @param AbstractModel $object
     * @param string $value
     * @param string|null $field
     * @return bool|int|string
     * @throws LocalizedException
     * @throws Exception
     */
    private function getOfferId(AbstractModel $object, string $value, string $field = null): bool|int|string
    {
        $identifierField = $this->getEntityMetadata()->getIdentifierField();
        $field = $field ?? $identifierField;
        $offerId = $value;
        if ($field != $identifierField || $object->getStoreId()) {
            $select = $this->_getLoadSelect($field, $value, $object);
            $select->reset(Zend_Db_Select::COLUMNS)
                ->columns($this->getMainTable() . '.' . $identifierField)
                ->limit(1);
            $result = $this->getConnection()->fetchCol($select);
            $offerId = count($result) ? $result[0] : false;
        }
        return $offerId;
    }

    /**
     * @param AbstractModel $object
     * @param $value
     * @param $field
     * @return Offer|$this
     * @throws LocalizedException
     */
    public function load(AbstractModel  $object, $value, $field = null): Offer|static
    {
        if ($offerId = $this->getOfferId($object, $value, $field)) {
            $this->entityManager->load($object, $offerId);
        }
        return $this;
    }

    /**
     * Retrieve select object for load object data
     * @param $field
     * @param $value
     * @param $object
     * @return Select
     * @throws LocalizedException
     */
    protected function _getLoadSelect($field, $value, $object): Select
    {
        $linkField = $this->getEntityMetadata()->getLinkField();
        $select = parent::_getLoadSelect($field, $value, $object);
        $storeId = $object->getStoreId();
        if ($storeId) {
            $tableStoreName = static::TABLE_STORE_NAME;
            $select->join(
                [$tableStoreName => $this->getTable($tableStoreName)],
                sprintf('%1$s.%3$s = %2$s.%3$s', $this->getMainTable(), $tableStoreName, $linkField),
                []
            )
                ->where('is_active = ?', 1)
                ->where($tableStoreName . '.store_id IN (?)', [Store::DEFAULT_STORE_ID, (int)$storeId])
                ->order($tableStoreName . '.store_id DESC')
                ->limit(1);
        }
        $categoryId= $object->getCategoryId();
        if ($categoryId) {
            $tableCategoryName = static::TABLE_CATEGORY_NAME;
            $select->join(
                [$tableCategoryName => $this->getTable($tableCategoryName)],
                sprintf('%1$s.%3$s = %2$s.%3$s', $this->getMainTable(), $tableCategoryName, $linkField),
                []
            )
                ->where($tableCategoryName . '.category_id IN (?)', [(int)$categoryId])
                ->order($tableCategoryName . '.category_id DESC')
                ->limit(1);
        }
        return $select;
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $offerId
     * @return array
     * @throws LocalizedException
     */
    public function lookupStoreIds(int $offerId): array
    {
        return $this->lookupEntityIds($offerId, static::TABLE_STORE_NAME, 'store_id');
    }

    /**
     * Get category ids to which specified item is assigned
     *
     * @param int $offerId
     * @return array
     * @throws LocalizedException
     */
    public function lookupCategoryIds(int $offerId): array
    {
        return $this->lookupEntityIds($offerId, static::TABLE_CATEGORY_NAME, 'category_id');
    }

    /**
     * @param int $entityId
     * @param string $joinTableName
     * @param string $selectedField
     * @return array
     * @throws LocalizedException
     */
    protected function lookupEntityIds(int $entityId, string $joinTableName, string $selectedField): array
    {
        $connection = $this->getConnection();
        $entityMetadata = $this->getEntityMetadata();
        $linkField = $entityMetadata->getLinkField();
        $identifierField = $entityMetadata->getIdentifierField();

        $select = $connection->select()
            ->from([$joinTableName => $this->getTable($joinTableName)], $selectedField)
            ->join(
                [static::TABLE_NAME => $this->getMainTable()],
                sprintf('%1$s.%3$s = %2$s.%3$s', $joinTableName, static::TABLE_NAME, $linkField),
                []
            )
            ->where(sprintf('%1$s.%2$s = :%2$s', static::TABLE_NAME, $identifierField));

        return $connection->fetchCol($select, [$identifierField => $entityId]);
    }

    /**
     * @return EntityMetadata|EntityMetadataInterface
     * @throws LocalizedException
     */
    private function getEntityMetadata(): EntityMetadataInterface|EntityMetadata
    {
        try {
            $entityMetadata = $this->metadataPool->getMetadata(OfferInterface::class);
        } catch (Exception $e) {
            $this->_logger->critical('Unable to load entity metadata', ['exception' => $e]);
            throw new LocalizedException(__($e->getMessage()));
        }
        return $entityMetadata;
    }

    /**
     * @inheritDoc
     */
    public function save(AbstractModel $object): AbstractDb|Offer|static
    {
        $object->beforeSave();
        $this->entityManager->save($object);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function delete(AbstractModel $object): AbstractDb|Offer|static
    {
        $this->entityManager->delete($object);
        return $this;
    }
}
