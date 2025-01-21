<?php

declare(strict_types=1);

namespace DnD\Offering\Test\Unit\Block\Category\Offer;

use DnD\Offering\Api\OfferRepositoryInterface;
use DnD\Offering\Block\Category\Offer\View;
use DnD\Offering\Model\Offer\FileInfo;
use DnD\Offering\Model\Offer\RedirectUrl;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use PHPUnit\Framework\TestCase;

final class ViewTest extends TestCase
{
    final public function testMocking()
    {
        $view = $this->getMockBuilder(View::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(View::class, $view);

    }

    final public function testInstantiationWithMocks()
    {
        $context = $this->createMock(Context::class);

        $offerRepository = $this->createMock(OfferRepositoryInterface::class);

        $registry = $this->createMock(Registry::class);

        $offerFileInfo = $this->createMock(FileInfo::class);

        $redirectUrl = $this->createMock(RedirectUrl::class);

        $dataObjectFactory = $this->createMock(DataObjectFactory::class);

        $view = new View($context, $offerRepository, $registry, $offerFileInfo, $redirectUrl, $dataObjectFactory);
        $this->assertInstanceOf(View::class, $view);

    }
}
