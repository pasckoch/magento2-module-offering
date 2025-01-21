<?php

declare(strict_types=1);

namespace DnD\Offering\Test\Unit\Ui\Component\Store;

use DnD\Offering\Ui\Component\Store\AllOptions;
use Magento\Framework\Escaper;
use Magento\Store\Model\System\Store;
use PHPUnit\Framework\TestCase;

final class AllOptionsTest extends TestCase
{
    final public function testMocking()
    {
        $allOptions = $this->getMockBuilder(AllOptions::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(AllOptions::class, $allOptions);

    }

    final public function testInstantiationWithMocks()
    {
        $systemStore = $this->createMock(Store::class);

        $escaper = $this->createMock(Escaper::class);

        $allOptions = new AllOptions($systemStore, $escaper);
        $this->assertInstanceOf(AllOptions::class, $allOptions);

    }
}
