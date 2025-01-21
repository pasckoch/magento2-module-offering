<?php

declare(strict_types=1);

namespace PascKoch\Offering\Test\Unit\Model\Offer\ResourceModel;

use PascKoch\Offering\Model\Offer\ResourceModel\Offer;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Model\ResourceModel\Db\Context;
use PHPUnit\Framework\TestCase;

final class OfferTest extends TestCase
{
    final public function testMocking()
    {
        $offer = $this->getMockBuilder(Offer::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(Offer::class, $offer);

    }

    final public function testInstantiationWithMocks()
    {
        $context = $this->createMock(Context::class);

        $metadataPool = $this->createMock(MetadataPool::class);

        $entityManager = $this->createMock(EntityManager::class);

        $offer = new Offer($context, $metadataPool, $entityManager);
        $this->assertInstanceOf(Offer::class, $offer);

    }
}
