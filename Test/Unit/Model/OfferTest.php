<?php

declare(strict_types=1);

namespace PascKoch\Offering\Test\Unit\Model;

use PascKoch\Offering\Model\Offer;
use PascKoch\Offering\Model\Offer\Image;
use PascKoch\Offering\Model\Offer\RedirectUrl;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
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

        $registry = $this->createMock(Registry::class);

        $image = $this->createMock(Image::class);

        $redirectUrlModel = $this->createMock(RedirectUrl::class);

        $categoryRepository = $this->createMock(CategoryRepositoryInterface::class);

        $offer = new Offer($context, $registry, $image, $redirectUrlModel, $categoryRepository);
        $this->assertInstanceOf(Offer::class, $offer);

    }
}
