<?php

namespace PascKoch\Offering\Model\Offer\ResourceModel\Listing;

use PascKoch\Offering\Model\Offer\ResourceModel\Listing\Collection\SearchResultTrait;
use PascKoch\Offering\Model\Offer\Collection as OfferCollection;
use PascKoch\Offering\Model\Offer\ResourceModel\AbstractCollection;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\Document;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * offer listing collection
 */
class Collection extends OfferCollection implements SearchResultInterface
{

    use SearchResultTrait;

    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        StoreManagerInterface $storeManager,
        MetadataPool $metadataPool,
        string $mainTable,
        string $eventPrefix,
        string $eventObject,
        protected string $resourceModel,
        protected TimezoneInterface $timeZone,
        protected string $model = Document::class,
        AdapterInterface $connection = null,
        AbstractDb $resource = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $storeManager, $metadataPool,
            $connection, $resource);
        $this->_eventPrefix = $eventPrefix;
        $this->_eventObject = $eventObject;
        $this->_init($this->model, $this->resourceModel);
        $this->setMainTable($mainTable);
    }

    /**
     * @inheritDoc
     */
    public function _resetState(): void
    {
        parent::_resetState();
        $this->_init($this->model, $this->resourceModel);
    }

    /**
     * @inheritDoc
     */
    public function addFieldToFilter($field, $condition = null): static
    {
        if (in_array($field, ['updated_at', 'date_from', 'date_to'])) {
            if (is_array($condition)) {
                foreach ($condition as $key => $value) {
                    try {
                        $condition[$key] = $this->timeZone->convertConfigTimeToUtc($value);
                    } catch (LocalizedException $e) {
                        $this->_logger->error(__('Error trying to filter a date'), ['exception' => $e]);
                    }
                }
            }
        }

        return parent::addFieldToFilter($field, $condition);
    }
}
