<?php

declare(strict_types=1);

namespace PascKoch\Offering\Test\Unit\Model;

use PascKoch\Offering\Api\Data\OfferSearchResultsInterfaceFactory;
use PascKoch\Offering\Model\Offer\CollectionFactory;
use PascKoch\Offering\Model\Offer\ResourceModel\Offer;
use PascKoch\Offering\Model\OfferFactory;
use PascKoch\Offering\Model\OfferRepository;
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
