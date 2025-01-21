<?php

declare(strict_types=1);

namespace DnD\Offering\Test\Unit\Controller\Adminhtml\Offer;

use DnD\Offering\Controller\Adminhtml\Offer\NewAction;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use PHPUnit\Framework\TestCase;

final class NewActionTest extends TestCase
{
    final public function testMocking()
    {
        $newAction = $this->getMockBuilder(NewAction::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(NewAction::class, $newAction);

    }

    final public function testInstantiationWithMocks()
    {
        $context = $this->createMock(Context::class);

        $resultForwardFactory = $this->createMock(ForwardFactory::class);

        $newAction = new NewAction($context, $resultForwardFactory);
        $this->assertInstanceOf(NewAction::class, $newAction);

    }
}
