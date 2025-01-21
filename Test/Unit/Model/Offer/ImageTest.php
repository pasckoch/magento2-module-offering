<?php

declare(strict_types=1);

namespace PascKoch\Offering\Test\Unit\Model\Offer;

use PascKoch\Offering\Model\Offer\FileInfo;
use PascKoch\Offering\Model\Offer\Image;
use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class ImageTest extends TestCase
{
    final public function testMocking()
    {
        $image = $this->getMockBuilder(Image::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(Image::class, $image);

    }

    final public function testInstantiationWithMocks()
    {
        $logger = $this->createMock(LoggerInterface::class);

        $filesystem = $this->createMock(Filesystem::class);

        $fileUploaderFactory = $this->createMock(UploaderFactory::class);

        $storeManager = $this->createMock(StoreManagerInterface::class);

        $imageUploader = $this->createMock(ImageUploader::class);

        $fileInfo = $this->createMock(FileInfo::class);

        $image = new Image($logger, $filesystem, $fileUploaderFactory, $storeManager, $imageUploader, $fileInfo);
        $this->assertInstanceOf(Image::class, $image);

    }
}
