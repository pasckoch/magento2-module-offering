<?php

declare(strict_types=1);

namespace DnD\Offering\Test\Unit\Controller\Adminhtml\Offer;

use DnD\Offering\Controller\Adminhtml\Offer\MassDelete;
use DnD\Offering\Model\Offer\CollectionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use PHPUnit\Framework\TestCase;

final class MassDeleteTest extends TestCase
{
    final public function testMocking()
    {
        $massDelete = $this->getMockBuilder(MassDelete::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(MassDelete::class, $massDelete);

    }

    final public function testInstantiationWithMocks()
    {
        $context = $this->createMock(Context::class);

        $filter = $this->createMock(Filter::class);

        $collectionFactory = $this->createMock(CollectionFactory::class);

        $massDelete = new MassDelete($context, $filter, $collectionFactory);
        $this->assertInstanceOf(MassDelete::class, $massDelete);

    }
}
