<?php

namespace PascKoch\Offering\Model\Offer;

use PascKoch\Offering\Api\Data\OfferInterface;
use Exception;
use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\File\Uploader;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class Image extends DataObject
{
    /**
     * @param LoggerInterface $logger
     * @param Filesystem $filesystem
     * @param UploaderFactory $fileUploaderFactory
     * @param StoreManagerInterface $storeManager
     * @param ImageUploader $imageUploader
     * @param FileInfo $fileInfo
     * @param array $data
     */
    public function __construct(
        protected LoggerInterface $logger,
        protected Filesystem $filesystem,
        protected UploaderFactory $fileUploaderFactory,
        protected StoreManagerInterface $storeManager,
        protected ImageUploader $imageUploader,
        protected FileInfo $fileInfo,
        array $data = [])
    {
        parent::__construct($data);

    }

    /**
     * @param $value
     * @return mixed|string
     */
    private function getUploadedImageName($value): mixed
    {
        if (is_array($value) && isset($value[0]['name'])) {
            return $value[0]['name'];
        }

        return '';
    }

    /**
     * @param string $imageName
     * @return false|string
     */
    private function checkUniqueImageName(string $imageName): false|string
    {
        try {
            $mediaDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);
            $imageAbsolutePath = $mediaDirectory->getAbsolutePath(
                $this->imageUploader->getBasePath() . DIRECTORY_SEPARATOR . $imageName
            );
        }
        catch (FileSystemException $e) {
            $this->logger->critical($e);
            return false;
        }

        return call_user_func([Uploader::class, 'getNewFilename'], $imageAbsolutePath);
    }

    /**
     * @param $object
     * @return void
     */
    public function save($object): void
    {
        $fieldName = OfferInterface::IMAGE_PATH;
        $value = $object->getData($fieldName);

        if ($this->isTmpFileAvailable($value) && $imageName = $this->getUploadedImageName($value)) {
            try {
                $store = $this->storeManager->getStore();
                $baseMediaDir = $store->getBaseMediaDir();
                $newImgRelativePath = $this->imageUploader->moveFileFromTmp($imageName, true);
                $value[0]['url'] = '/' . $baseMediaDir . '/' . $newImgRelativePath;
                $value[0]['name'] = $value[0]['url'];
            } catch (NoSuchEntityException|Exception $e) {
                $this->logger->error($e);
            }
        } elseif ($this->fileResidesOutsideSaveDir($value)) {
            $value[0]['url'] = parse_url($value[0]['url'], PHP_URL_PATH);
            $value[0]['name'] = $value[0]['url'];
        }

        if ($imageName = $this->getUploadedImageName($value)) {
            if (!$this->fileResidesOutsideSaveDir($value)) {
                $imageName = $this->checkUniqueImageName($imageName);
            }
            try {
                $absoluteImageName = BP.'/pub'.$imageName;
                if(!$this->fileInfo->resizeImage($absoluteImageName)){
                    throw new LocalizedException(__('Unable to resize image %1.',$absoluteImageName));
                }
            }
            catch(Throwable $e){
                $this->logger->error($e->getMessage());
            }
            $object->setData($fieldName, $imageName);
        } elseif (!is_string($value)) {
            $object->setData($fieldName, null);
        }
    }

    /**
     * Check if temporary file is available for new image upload.
     *
     * @param array $value
     * @return bool
     */
    private function isTmpFileAvailable(array $value): bool
    {
        return isset($value[0]['tmp_name']);
    }

    /**
     * Check for file path resides outside of save media dir. The URL will be a path including pub/media if true
     *
     * @param array|null $value
     * @return bool
     */
    private function fileResidesOutsideSaveDir(?array $value): bool
    {
        if (!is_array($value) || !isset($value[0]['url'])) {
            return false;
        }

        $fileUrl = ltrim($value[0]['url'], '/');
        $baseMediaDir = $this->filesystem->getUri(DirectoryList::MEDIA);

        if (!$baseMediaDir) {
            return false;
        }

        return str_contains($fileUrl, $baseMediaDir);
    }
}
