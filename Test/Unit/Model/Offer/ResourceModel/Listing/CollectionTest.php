<?php

declare(strict_types=1);

namespace PascKoch\Offering\Test\Unit\Model\Offer\ResourceModel\Listing;

use PascKoch\Offering\Model\Offer\ResourceModel\Listing\Collection;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class CollectionTest extends TestCase
{
    final public function testMocking()
    {
        $collection = $this->getMockBuilder(Collection::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(Collection::class, $collection);

    }

    final public function testInstantiationWithMocks()
    {
        $entityFactory = $this->createMock(EntityFactoryInterface::class);

        $logger = $this->createMock(LoggerInterface::class);

        $fetchStrategy = $this->createMock(FetchStrategyInterface::class);

        $eventManager = $this->createMock(ManagerInterface::class);

        $storeManager = $this->createMock(StoreManagerInterface::class);

        $metadataPool = $this->createMock(MetadataPool::class);

        $timeZone = $this->createMock(TimezoneInterface::class);

        $mainTable ='pasckoch_offer';

        $eventPrefix= 'offering_offer_listing_collection';

        $eventObject ='offer_listing_collection';

        $resourceModel = 'PascKoch\Offering\Model\Offer\ResourceModel\Offer';

        $collection = new Collection($entityFactory, $logger, $fetchStrategy, $eventManager, $storeManager, $metadataPool, $mainTable, $eventPrefix, $eventObject, $resourceModel, $timeZone);
        $this->assertInstanceOf(Collection::class, $collection);

    }
}
