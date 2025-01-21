<?php

declare(strict_types=1);

namespace PascKoch\Offering\Test\Unit\Model\Offer;

use PascKoch\Offering\Model\Offer\RedirectUrl;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Catalog\Model\ProductRepository;
use Magento\Cms\Helper\Page;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\TestCase;

final class RedirectUrlTest extends TestCase
{
    final public function testMocking()
    {
        $redirectUrl = $this->getMockBuilder(RedirectUrl::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(RedirectUrl::class, $redirectUrl);

    }

    final public function testInstantiationWithMocks()
    {
        $json = $this->createMock(Json::class);

        $storeManager = $this->createMock(StoreManagerInterface::class);

        $categoryRepository = $this->createMock(CategoryRepository::class);

        $productRepository = $this->createMock(ProductRepository::class);

        $pageHelper = $this->createMock(Page::class);

        $redirectUrl = new RedirectUrl($json, $storeManager, $categoryRepository, $productRepository, $pageHelper);
        $this->assertInstanceOf(RedirectUrl::class, $redirectUrl);

    }
}
