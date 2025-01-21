<?php

declare(strict_types=1);

namespace PascKoch\Offering\Test\Unit\Block\Adminhtml\Offer\Edit;

use PascKoch\Offering\Api\OfferRepositoryInterface;
use PascKoch\Offering\Block\Adminhtml\Offer\Edit\SaveButton;
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
