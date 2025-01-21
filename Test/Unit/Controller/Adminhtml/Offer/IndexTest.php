<?php

declare(strict_types=1);

namespace DnD\Offering\Test\Unit\Controller\Adminhtml\Offer;

use DnD\Offering\Controller\Adminhtml\Offer\Index;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use PHPUnit\Framework\TestCase;

final class IndexTest extends TestCase
{
    final public function testMocking()
    {
        $index = $this->getMockBuilder(Index::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(Index::class, $index);

    }

    final public function testInstantiationWithMocks()
    {
        $context = $this->createMock(Context::class);

        $resultPageFactory = $this->createMock(PageFactory::class);

        $index = new Index($context, $resultPageFactory);
        $this->assertInstanceOf(Index::class, $index);

    }
}
