<?php

declare(strict_types=1);

namespace DnD\Offering\Test\Unit\Block\Adminhtml\Offer\Edit;

use DnD\Offering\Api\OfferRepositoryInterface;
use DnD\Offering\Block\Adminhtml\Offer\Edit\SaveButton;
use Magento\Backend\Block\Widget\Context;
use PHPUnit\Framework\TestCase;

final class SaveButtonTest extends TestCase
{
    final public function testMocking()
    {
        $saveButton = $this->getMockBuilder(SaveButton::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(SaveButton::class, $saveButton);

    }

    final public function testInstantiationWithMocks()
    {
        $context = $this->createMock(Context::class);

        $offerRepository = $this->createMock(OfferRepositoryInterface::class);

        $saveButton = new SaveButton($context, $offerRepository);
        $this->assertInstanceOf(SaveButton::class, $saveButton);

    }
}
