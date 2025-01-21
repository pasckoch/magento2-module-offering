<?php

declare(strict_types=1);

namespace DnD\Offering\Test\Unit\Controller\Adminhtml\Offer\Image;

use DnD\Offering\Controller\Adminhtml\Offer\Image\Upload;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Model\ImageUploader;
use PHPUnit\Framework\TestCase;

final class UploadTest extends TestCase
{
    final public function testMocking()
    {
        $upload = $this->getMockBuilder(Upload::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(Upload::class, $upload);

    }

    final public function testInstantiationWithMocks()
    {
        $context = $this->createMock(Context::class);

        $imageUploader = $this->createMock(ImageUploader::class);

        $upload = new Upload($context, $imageUploader);
        $this->assertInstanceOf(Upload::class, $upload);

    }
}
