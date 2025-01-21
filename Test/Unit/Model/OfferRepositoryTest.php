<?php

declare(strict_types=1);

namespace DnD\Offering\Test\Unit\Model;

use DnD\Offering\Api\Data\OfferSearchResultsInterfaceFactory;
use DnD\Offering\Model\Offer\CollectionFactory;
use DnD\Offering\Model\Offer\ResourceModel\Offer;
use DnD\Offering\Model\OfferFactory;
use DnD\Offering\Model\OfferRepository;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\TestCase;

final class OfferRepositoryTest extends TestCase
{
    final public function testMocking()
    {
        $offerRepository = $this->getMockBuilder(OfferRepository::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(OfferRepository::class, $offerRepository);

    }

    final public function testInstantiationWithMocks()
    {
        $offerFactory = $this->createMock(OfferFactory::class);

        $offerResource = $this->createMock(Offer::class);

        $offerCollectionFactory = $this->createMock(CollectionFactory::class);

        $offerSearchResultsFactory = $this->createMock(OfferSearchResultsInterfaceFactory::class);

        $collectionProcessor = $this->createMock(CollectionProcessorInterface::class);

        $searchCriteriaBuilder = $this->createMock(SearchCriteriaBuilder::class);

        $sortOrderBuilder = $this->createMock(SortOrderBuilder::class);

        $storeManager = $this->createMock(StoreManagerInterface::class);

        $timezone = $this->createMock(TimezoneInterface::class);

        $offerRepository = new OfferRepository($offerFactory, $offerResource, $offerCollectionFactory, $offerSearchResultsFactory, $collectionProcessor, $searchCriteriaBuilder, $sortOrderBuilder, $storeManager, $timezone);
        $this->assertInstanceOf(OfferRepository::class, $offerRepository);

    }
}
