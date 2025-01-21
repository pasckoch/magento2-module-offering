<?php

declare(strict_types=1);

namespace DnD\Offering\Test\Unit\Model\Offer\ResourceModel\Relation\Store;

use DnD\Offering\Model\Offer\ResourceModel\Offer;
use DnD\Offering\Model\Offer\ResourceModel\Relation\Store\SaveHandler;
use Magento\Framework\EntityManager\MetadataPool;
use PHPUnit\Framework\TestCase;

final class SaveHandlerTest extends TestCase
{
    final public function testMocking()
    {
        $saveHandler = $this->getMockBuilder(SaveHandler::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(SaveHandler::class, $saveHandler);

    }

    final public function testInstantiationWithMocks()
    {
        $metadataPool = $this->createMock(MetadataPool::class);

        $resource = $this->createMock(Offer::class);

        $saveHandler = new SaveHandler($metadataPool, $resource);
        $this->assertInstanceOf(SaveHandler::class, $saveHandler);

    }
}
