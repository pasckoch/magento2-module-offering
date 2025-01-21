<?php

declare(strict_types=1);

namespace DnD\Offering\Test\Unit\Model\Offer\ResourceModel\Relation\Store;

use DnD\Offering\Model\Offer\ResourceModel\Offer;
use DnD\Offering\Model\Offer\ResourceModel\Relation\Store\ReadHandler;
use Magento\Framework\EntityManager\MetadataPool;
use PHPUnit\Framework\TestCase;

final class ReadHandlerTest extends TestCase
{
    final public function testMocking()
    {
        $readHandler = $this->getMockBuilder(ReadHandler::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(ReadHandler::class, $readHandler);

    }

    final public function testInstantiationWithMocks()
    {
        $metadataPool = $this->createMock(MetadataPool::class);

        $resource = $this->createMock(Offer::class);

        $readHandler = new ReadHandler($metadataPool, $resource);
        $this->assertInstanceOf(ReadHandler::class, $readHandler);

    }
}
