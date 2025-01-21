<?php

declare(strict_types=1);

namespace PascKoch\Offering\Test\Unit\Model\Offer;

use PascKoch\Offering\Model\Offer\FileInfo;
use Magento\Framework\File\Mime;
use Magento\Framework\Filesystem;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\TestCase;

final class FileInfoTest extends TestCase
{
    final public function testMocking()
    {
        $fileInfo = $this->getMockBuilder(FileInfo::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(FileInfo::class, $fileInfo);

    }

    final public function testInstantiationWithMocks()
    {
        $filesystem = $this->createMock(Filesystem::class);

        $mime = $this->createMock(Mime::class);

        $storeManager = $this->createMock(StoreManagerInterface::class);

        $fileInfo = new FileInfo($filesystem, $mime, $storeManager);
        $this->assertInstanceOf(FileInfo::class, $fileInfo);

    }
}
