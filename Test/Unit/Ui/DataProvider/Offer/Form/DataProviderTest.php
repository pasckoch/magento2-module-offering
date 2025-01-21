<?php

declare(strict_types=1);

namespace PascKoch\Offering\Test\Unit\Ui\DataProvider\Offer\Form;

use PascKoch\Offering\Api\OfferRepositoryInterface;
use PascKoch\Offering\Model\Offer\CollectionFactory;
use PascKoch\Offering\Model\Offer\FileInfo;
use PascKoch\Offering\Model\Offer\RedirectUrl;
use PascKoch\Offering\Model\OfferFactory;
use PascKoch\Offering\Ui\DataProvider\Offer\Form\DataProvider;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use PHPUnit\Framework\TestCase;

final class DataProviderTest extends TestCase
{
    final public function testMocking()
    {
        $dataProvider = $this->getMockBuilder(DataProvider::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(DataProvider::class, $dataProvider);

    }

    final public function testInstantiationWithMocks()
    {
        $offerCollectionFactory = $this->createMock(CollectionFactory::class);

        $dataPersistor = $this->createMock(DataPersistorInterface::class);

        $pool = $this->createMock(PoolInterface::class);

        $request = $this->createMock(RequestInterface::class);

        $offerRepository = $this->createMock(OfferRepositoryInterface::class);

        $offerFactory = $this->createMock(OfferFactory::class);

        $fileInfo = $this->createMock(FileInfo::class);

        $redirectUrl = $this->createMock(RedirectUrl::class);

        $dataProvider = new DataProvider($name, $primaryFieldName, $requestFieldName, $offerCollectionFactory, $dataPersistor, $pool, $request, $offerRepository, $offerFactory, $fileInfo, $redirectUrl);
        $this->assertInstanceOf(DataProvider::class, $dataProvider);

    }
}
