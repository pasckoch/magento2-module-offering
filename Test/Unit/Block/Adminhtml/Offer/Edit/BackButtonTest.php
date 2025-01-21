<?php

declare(strict_types=1);

namespace PascKoch\Offering\Test\Unit\Block\Adminhtml\Offer\Edit;

use PascKoch\Offering\Api\OfferRepositoryInterface;
use PascKoch\Offering\Block\Adminhtml\Offer\Edit\BackButton;
use Magento\Backend\Block\Widget\Context;
use PHPUnit\Framework\TestCase;

final class BackButtonTest extends TestCase
{
    final public function testMocking()
    {
        $backButton = $this->getMockBuilder(BackButton::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(BackButton::class, $backButton);

    }

    final public function testInstantiationWithMocks()
    {
        $context = $this->createMock(Context::class);

        $offerRepository = $this->createMock(OfferRepositoryInterface::class);

        $backButton = new BackButton($context, $offerRepository);
        $this->assertInstanceOf(BackButton::class, $backButton);

    }
}
