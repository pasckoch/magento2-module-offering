<?php

declare(strict_types=1);

namespace PascKoch\Offering\Test\Unit\Ui\Component\Category;

use PascKoch\Offering\Ui\Component\Category\Options;
use Magento\Backend\Model\Auth\Session;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Serialize\SerializerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class OptionsTest extends TestCase
{
    final public function testMocking()
    {
        $options = $this->getMockBuilder(Options::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(Options::class, $options);

    }

    final public function testInstantiationWithMocks()
    {
        $categoryCollectionFactory = $this->createMock(CollectionFactory::class);

        $session = $this->createMock(Session::class);

        $cache = $this->createMock(CacheInterface::class);

        $serializer = $this->createMock(SerializerInterface::class);

        $logger = $this->createMock(LoggerInterface::class);

        $options = new Options($categoryCollectionFactory, $session, $cache, $serializer, $logger);
        $this->assertInstanceOf(Options::class, $options);

    }
}
