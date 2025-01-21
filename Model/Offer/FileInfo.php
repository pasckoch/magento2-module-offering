<?php

namespace PascKoch\Offering\Model\Offer;

use GdImage;
use Magento\Catalog\Model\Category\FileInfo as CategoryFileInfo;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\File\Mime;
use Magento\Framework\Filesystem;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class FileInfo extends CategoryFileInfo
{
    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly Mime $mime,
        private readonly StoreManagerInterface $storeManager
    ) {
        parent::__construct($this->filesystem, $this->mime, $this->storeManager);
    }

    /**
     * @param string $image
     * @return string
     */
    public function getUrl(string $image): string
    {
        try {
            $store = $this->storeManager->getStore();
            $mediaBaseUrl = $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
            if ($this->isBeginsWithMediaDirectoryPath($image)) {
                $relativePath = $this->getRelativePathToMediaDirectory($image);
                $url = rtrim($mediaBaseUrl, '/') . '/' . ltrim($relativePath, '/');
            } elseif (!str_starts_with($image, '/')) {
                $url = rtrim($mediaBaseUrl, '/') . '/' . ltrim(CategoryFileInfo::ENTITY_MEDIA_PATH, '/') . '/' . $image;
            } else {
                $url = $image;
            }
        } catch (NoSuchEntityException) {
            $url = $image;
        }
        return $url;
    }

    /**
     * @param string $imagePath
     * @param int $destHeight
     * @return bool
     */
    public function resizeImage(string $imagePath, int $destHeight = 200): bool
    {
        if (!is_file($imagePath)) {
            return false;
        }
        list($width, $height, $type, $attr) = getimagesize($imagePath);
        $destWidth = (int)($width * $destHeight / $height);
        if ((int)$width === $destWidth && (int)$height === $destHeight) {
            return true;
        }
        $isCopy = false;
        switch ($type) {
            case IMAGETYPE_GIF:
                $imageSource = imagecreatefromgif($imagePath);
                $imageDest = $this->createImageResize($imageSource, $width, $height, $destWidth, $destHeight);
                if ($imageDest) {
                    $isCopy = imagegif($imageDest, $imagePath);
                }
                break;
            case IMAGETYPE_JPEG:
                $imageSource = imagecreatefromjpeg($imagePath);
                $imageDest = $this->createImageResize($imageSource, $width, $height, $destWidth, $destHeight);
                if ($imageDest) {
                    $isCopy = imagejpeg($imageDest, $imagePath);
                }
                break;
            case IMAGETYPE_PNG:
                $imageSource = imagecreatefrompng($imagePath);
                $imageDest = $this->createImageResize($imageSource, $width, $height, $destWidth, $destHeight);
                if ($imageDest) {
                    $isCopy = imagepng($imageDest, $imagePath);
                }
                break;
        }
        return $isCopy;
    }

    /**
     * @param $imageSource
     * @param int $imageSourceWidth
     * @param int $imageSourceHeight
     * @param int $imageDestWidth
     * @param int $imageDestHeight
     * @return false|GdImage|resource
     */
    public function createImageResize(
        $imageSource,
        int $imageSourceWidth,
        int $imageSourceHeight,
        int $imageDestWidth,
        int $imageDestHeight
    ) {
        $imageDest = imagecreatetruecolor($imageDestWidth, $imageDestHeight);
        imagecopyresampled($imageDest, $imageSource, 0, 0, 0, 0, $imageDestWidth, $imageDestHeight, $imageSourceWidth,
            $imageSourceHeight);
        return $imageDest;
    }

}
