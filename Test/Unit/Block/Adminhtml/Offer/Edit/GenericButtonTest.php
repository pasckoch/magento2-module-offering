<?php

declare(strict_types=1);

namespace DnD\Offering\Test\Unit\Block\Adminhtml\Offer\Edit;

use DnD\Offering\Api\OfferRepositoryInterface;
use DnD\Offering\Block\Adminhtml\Offer\Edit\GenericButton;
use Magento\Backend\Block\Widget\Context;
use PHPUnit\Framework\TestCase;

final class GenericButtonTest extends TestCase
{
    final public function testMocking()
    {
        $genericButton = $this->getMockBuilder(GenericButton::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(GenericButton::class, $genericButton);

    }

    final public function testInstantiationWithMocks()
    {
        $context = $this->createMock(Context::class);

        $offerRepository = $this->createMock(OfferRepositoryInterface::class);

        $genericButton = new GenericButton($context, $offerRepository);
        $this->assertInstanceOf(GenericButton::class, $genericButton);

    }
}
