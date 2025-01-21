<?php

declare(strict_types=1);

namespace DnD\Offering\Test\Unit\Controller\Adminhtml\Offer;

use DnD\Offering\Api\OfferRepositoryInterface;
use DnD\Offering\Controller\Adminhtml\Offer\Delete;
use Magento\Backend\App\Action\Context;
use PHPUnit\Framework\TestCase;

final class DeleteTest extends TestCase
{
    final public function testMocking()
    {
        $delete = $this->getMockBuilder(Delete::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(Delete::class, $delete);

    }

    final public function testInstantiationWithMocks()
    {
        $context = $this->createMock(Context::class);

        $offerRepository = $this->createMock(OfferRepositoryInterface::class);

        $delete = new Delete($context, $offerRepository);
        $this->assertInstanceOf(Delete::class, $delete);

    }
}
