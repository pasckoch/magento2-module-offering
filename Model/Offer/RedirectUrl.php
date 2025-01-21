<?php

namespace PascKoch\Offering\Model\Offer;

use PascKoch\Offering\Api\Data\OfferInterface;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Catalog\Model\ProductRepository;
use Magento\Cms\Helper\Page;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\StoreManagerInterface;

class RedirectUrl extends DataObject
{
    /** @var string */
    public const SETTING = 'settings';

    /** @var string */
    public const TYPE = 'type';

    /** @var string */
    public const DEFAULT = 'default';

    /** @var string */
    protected string $url;

    /**
     * @param Json $json
     * @param array $data
     */
    public function __construct(
        protected Json $json,
        protected StoreManagerInterface $storeManager,
        protected CategoryRepository $categoryRepository,
        protected ProductRepository $productRepository,
        protected Page $pageHelper,
        array $data = []
    ) {
        parent::__construct($data);
    }

    /**
     * @param DataObject $object
     * @return void
     */
    public function encodeRedirectUrlJson(DataObject $object): void
    {
        $object->setData(
            OfferInterface::REDIRECT_URL,
            $this->json->serialize($object->getData(OfferInterface::REDIRECT_URL))
        );
    }

    /**
     * @param DataObject $object
     * @return $this
     */
    public function decodeRedirectUrlJson(DataObject $object): static
    {
        $object->setData(
            OfferInterface::REDIRECT_URL,
            $this->json->unserialize($object->getData(OfferInterface::REDIRECT_URL))
        );
        if (is_array($object->getData(OfferInterface::REDIRECT_URL))) {
            $redirectUrl = $object->getData(OfferInterface::REDIRECT_URL);
            $this->setSetting($redirectUrl['setting']);
            $this->setType($redirectUrl['type']);
            $this->setDefault($redirectUrl['default']);
            $this->setUrl($redirectUrl);
        }
        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param array $redirectUrl
     * @return $this
     */
    public function setUrl(array $redirectUrl): static
    {
        $url = '';
        try {
            switch ($this->getType()) {
                case 'default':
                    $url = $this->getDefault();
                    break;
                case 'category':
                    $url = $this->categoryRepository->get($redirectUrl['category'],
                        $this->storeManager->getStore()->getId())->getUrl();
                    break;
                case 'product':
                    $url = $this->productRepository->getById($redirectUrl['product'], false,
                        $this->storeManager->getStore()->getId())->getProductUrl();
                    break;
                case 'page':
                    $url = $this->pageHelper->getPageUrl($redirectUrl['page']);
                    break;
            }
        } catch (NoSuchEntityException) {
        }
        $this->url = $url;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSetting(): ?string
    {
        return $this->getData(static::SETTING);
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->getData(static::TYPE);
    }

    /**
     * @return string|null
     */
    public function getDefault(): ?string
    {
        return $this->getData(static::DEFAULT);
    }

    /**
     * @param string|null $setting
     * @return $this
     */
    public function setSetting(?string $setting): RedirectUrl
    {
        $this->setData(static::SETTING, $setting);
        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type): RedirectUrl
    {
        $this->setData(static::TYPE, $type);
        return $this;
    }

    /**
     * @param string $default
     * @return $this
     */
    public function setDefault(string $default): RedirectUrl
    {
        $this->setData(static::DEFAULT, $default);
        return $this;
    }

}
