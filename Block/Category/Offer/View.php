<?php

namespace PascKoch\Offering\Block\Category\Offer;

use PascKoch\Offering\Api\Data\OfferInterface;
use PascKoch\Offering\Api\Data\OfferSearchResultsInterface;
use PascKoch\Offering\Api\OfferRepositoryInterface;
use PascKoch\Offering\Model\Offer\FileInfo;
use PascKoch\Offering\Model\Offer\RedirectUrl;
use Magento\Framework\DataObject;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Store\Api\Data\StoreInterface;

class View extends Template
{
    /**
     * @param Template\Context $context
     * @param OfferRepositoryInterface $offerRepository
     * @param Registry $registry
     * @param FileInfo $offerFileInfo
     * @param RedirectUrl $redirectUrl
     * @param DataObjectFactory $dataObjectFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        protected OfferRepositoryInterface $offerRepository,
        protected Registry $registry,
        protected FileInfo $offerFileInfo,
        protected RedirectUrl $redirectUrl,
        protected DataObjectFactory $dataObjectFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @return OfferSearchResultsInterface
     */
    public function getOfferCollection(): OfferSearchResultsInterface
    {
        $store = $this->getStore();
        return $this->offerRepository->getListForToday($this->getCurrentCategory(), [$store ? $store->getId() : 0]);
    }

    /**
     * @return array|mixed|null
     */
    public function getCurrentCategory(): mixed
    {
        if (!$this->hasData('current_category')) {
            $this->setData('current_category', $this->registry->registry('current_category'));
        }
        return $this->getData('current_category');
    }

    /**
     * @param string $imagePath
     * @return string
     */
    public function getUrlImage(string $imagePath): string
    {
        return $this->offerFileInfo->getUrl($imagePath);
    }

    /**
     * @param string $redirectUrlJson
     * @return DataObject
     */
    public function getTargetUrl(string $redirectUrlJson): DataObject
    {
        $object = $this->dataObjectFactory->create();
        $object->setData(OfferInterface::REDIRECT_URL, $redirectUrlJson);
        $redirectUrl = $this->redirectUrl->decodeRedirectUrlJson($object);
        $target = $this->dataObjectFactory->create();
        $target->setData('url',$redirectUrl->getUrl());
        $target->setData('target',$redirectUrl->getSetting()==='true' ? '_blank' : '_self');
        return $target;
    }

    /**
     * @return StoreInterface|null
     */
    private function getStore(): ?StoreInterface
    {
        try {
            $store = $this->_storeManager->getStore();
        } catch (NoSuchEntityException) {
            $store = $this->_storeManager->getDefaultStoreView();
        }
        return $store;
    }
}
