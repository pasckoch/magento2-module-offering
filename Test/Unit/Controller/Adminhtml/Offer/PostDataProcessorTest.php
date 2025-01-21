<?php

declare(strict_types=1);

namespace PascKoch\Offering\Test\Unit\Controller\Adminhtml\Offer;

use PascKoch\Offering\Controller\Adminhtml\Offer\PostDataProcessor;
use PascKoch\Offering\Model\Offer\CollectionFactory;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use PHPUnit\Framework\TestCase;

final class PostDataProcessorTest extends TestCase
{
    final public function testMocking()
    {
        $postDataProcessor = $this->getMockBuilder(PostDataProcessor::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(PostDataProcessor::class, $postDataProcessor);

    }

    final public function testInstantiationWithMocks()
    {
        $dateFilter = $this->createMock(Date::class);

        $messageManager = $this->createMock(ManagerInterface::class);

        $offerCollectionFactory = $this->createMock(CollectionFactory::class);

        $categoryRepository = $this->createMock(CategoryRepositoryInterface::class);

        $postDataProcessor = new PostDataProcessor($dateFilter, $messageManager, $offerCollectionFactory, $categoryRepository);
        $this->assertInstanceOf(PostDataProcessor::class, $postDataProcessor);

    }
}
