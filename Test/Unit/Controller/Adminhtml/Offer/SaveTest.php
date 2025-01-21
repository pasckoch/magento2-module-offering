<?php

declare(strict_types=1);

namespace DnD\Offering\Test\Unit\Controller\Adminhtml\Offer;

use DnD\Offering\Api\OfferRepositoryInterface;
use DnD\Offering\Controller\Adminhtml\Offer\PostDataProcessor;
use DnD\Offering\Controller\Adminhtml\Offer\Save;
use DnD\Offering\Model\OfferFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class SaveTest extends TestCase
{
    final public function testMocking()
    {
        $save = $this->getMockBuilder(Save::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(Save::class, $save);

    }

    final public function testInstantiationWithMocks()
    {
        $context = $this->createMock(Context::class);

        $dataProcessor = $this->createMock(PostDataProcessor::class);

        $dataPersistor = $this->createMock(DataPersistorInterface::class);

        $offerFactory = $this->createMock(OfferFactory::class);

        $offerRepository = $this->createMock(OfferRepositoryInterface::class);

        $logger = $this->createMock(LoggerInterface::class);

        $save = new Save($context, $dataProcessor, $dataPersistor, $offerFactory, $offerRepository, $logger);
        $this->assertInstanceOf(Save::class, $save);

    }
}
