<?php

declare(strict_types=1);

namespace PascKoch\Offering\Test\Unit\Controller\Adminhtml\Offer;

use PascKoch\Offering\Api\OfferRepositoryInterface;
use PascKoch\Offering\Controller\Adminhtml\Offer\Edit;
use PascKoch\Offering\Model\OfferFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use PHPUnit\Framework\TestCase;

final class EditTest extends TestCase
{
    final public function testMocking()
    {
        $edit = $this->getMockBuilder(Edit::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(Edit::class, $edit);

    }

    final public function testInstantiationWithMocks()
    {
        $context = $this->createMock(Context::class);

        $resultPageFactory = $this->createMock(PageFactory::class);

        $registry = $this->createMock(Registry::class);

        $offerFactory = $this->createMock(OfferFactory::class);

        $offerRepository = $this->createMock(OfferRepositoryInterface::class);

        $edit = new Edit($context, $resultPageFactory, $registry, $offerFactory, $offerRepository);
        $this->assertInstanceOf(Edit::class, $edit);

    }
}
