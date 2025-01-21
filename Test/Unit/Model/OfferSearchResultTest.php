<?php

declare(strict_types=1);

namespace DnD\Offering\Test\Unit\Model;

use DnD\Offering\Model\OfferSearchResult;
use PHPUnit\Framework\TestCase;

final class OfferSearchResultTest extends TestCase
{
    final public function testMocking()
    {
        $offerSearchResult = $this->getMockBuilder(OfferSearchResult::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(OfferSearchResult::class, $offerSearchResult);

    }

    final public function testInstantiationWithMocks()
    {
        $offerSearchResult = new OfferSearchResult();
        $this->assertInstanceOf(OfferSearchResult::class, $offerSearchResult);

    }
}
