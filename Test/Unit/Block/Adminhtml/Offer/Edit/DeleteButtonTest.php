<?php

declare(strict_types=1);

namespace PascKoch\Offering\Test\Unit\Block\Adminhtml\Offer\Edit;

use PascKoch\Offering\Api\OfferRepositoryInterface;
use PascKoch\Offering\Block\Adminhtml\Offer\Edit\DeleteButton;
use Magento\Backend\Block\Widget\Context;
use PHPUnit\Framework\TestCase;

final class DeleteButtonTest extends TestCase
{
    final public function testMocking()
    {
        $deleteButton = $this->getMockBuilder(DeleteButton::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(DeleteButton::class, $deleteButton);

    }

    final public function testInstantiationWithMocks()
    {
        $context = $this->createMock(Context::class);

        $offerRepository = $this->createMock(OfferRepositoryInterface::class);

        $deleteButton = new DeleteButton($context, $offerRepository);
        $this->assertInstanceOf(DeleteButton::class, $deleteButton);

    }
}
