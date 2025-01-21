<?php

declare(strict_types=1);

namespace DnD\Offering\Test\Unit\Model\Offer\ResourceModel\Relation;

use DnD\Offering\Model\Offer\ResourceModel\Offer;
use DnD\Offering\Model\Offer\ResourceModel\Relation\AbstractHandler;
use Magento\Framework\EntityManager\MetadataPool;
use PHPUnit\Framework\TestCase;

final class AbstractHandlerTest extends TestCase
{
    final public function testMocking()
    {
        $abstractHandler = $this->getMockBuilder(AbstractHandler::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(AbstractHandler::class, $abstractHandler);

    }

    final public function testInstantiationWithMocks()
    {
        $metadataPool = $this->createMock(MetadataPool::class);

        $resource = $this->createMock(Offer::class);

        $abstractHandler = new AbstractHandler($metadataPool, $resource);
        $this->assertInstanceOf(AbstractHandler::class, $abstractHandler);

    }
}
