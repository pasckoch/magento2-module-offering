<?php

namespace PascKoch\Offering\Model;

use PascKoch\Offering\Api\Data\OfferInterface;
use PascKoch\Offering\Model\Offer\Image;
use PascKoch\Offering\Model\Offer\RedirectUrl;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\ResourceModel\Category\Collection as CategoryCollection;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

/**
 * @method Offer setStoreId(int $storeId)
 * @method int getStoreId()
 * @method Category setCategoryId(int $categoryId)
 * @method int getCategoryId()
 */
class Offer extends AbstractModel implements OfferInterface
{

    /** @var Image  */
    protected Image $image;

    /** @var RedirectUrl  */
    protected RedirectUrl $redirectUrlModel;

    /** @var CategoryCollection  */
    protected CategoryCollection $categoryCollection;

    /** @var CategoryRepositoryInterface  */
    protected CategoryRepositoryInterface $categoryRepository;

    public function __construct(
        Context $context,
        Registry $registry,
        Image $image,
        RedirectUrl $redirectUrlModel,
        CategoryRepositoryInterface $categoryRepository,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->image = $image;
        $this->redirectUrlModel = $redirectUrlModel;
        $this->categoryRepository = $categoryRepository;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(Offer\ResourceModel\Offer::class);
        $this->setIdFieldName(OfferInterface::OFFER_ID);
    }

    /**
     * @return $this|Offer
     */
    public function beforeSave(): Offer|static
    {
        parent::beforeSave();
        if($this->getData(OfferInterface::IMAGE_PATH) && is_array($this->getData(OfferInterface::IMAGE_PATH))) {
            $this->image->save($this);
        }

        if($this->getData(OfferInterface::REDIRECT_URL) && is_array($this->getData(OfferInterface::REDIRECT_URL))){
            $this->redirectUrlModel->encodeRedirectUrlJson($this);
        }
        return $this;
    }

    /**
     * Getter id
     * @return ?int
     */
    public function getId(): ?int
    {
        return $this->getData(OfferInterface::OFFER_ID);
    }

    /**
     * Getter offer title
     * @return string
     */
    public function getTitle(): string
    {
        return (string)$this->getData(OfferInterface::TITLE);
    }

    /**
     * Setter offer title
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): OfferInterface
    {
        return $this->setData(OfferInterface::TITLE, $title);
    }

    /**
     * Getter offer label
     * @return string
     */
    public function getLabel(): string
    {
        return (string)$this->getData(OfferInterface::LABEL);
    }

    /**
     * Setter offer label
     * @param string $label
     * @return $this
     */
    public function setLabel(string $label): OfferInterface
    {
        return $this->setData(OfferInterface::LABEL, $label);
    }

    /**
     * Getter offer image path
     * @return string|null
     */
    public function getImagePath(): ?string
    {
        return $this->getData(OfferInterface::IMAGE_PATH);
    }

    /**
     * @param string|null $imagePath
     * @return OfferInterface
     */
    public function setImagePath(?string $imagePath): OfferInterface
    {
        return $this->setData(OfferInterface::IMAGE_PATH, $imagePath);
    }

    /**
     * Getter offer redirect url
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return (string)$this->getData(OfferInterface::REDIRECT_URL);
    }

    /**
     * Setter offer redirect url
     * @param string $redirectUrl
     * @return $this
     */
    public function setRedirectUrl(string $redirectUrl): OfferInterface
    {
        return $this->setData(OfferInterface::REDIRECT_URL, $redirectUrl);
    }

    /**
     * Getter offer from date
     * @return ?string
     */
    public function getDateFrom(): ?string
    {
        return $this->getData(OfferInterface::DATE_FROM);
    }

    /**
     * Setter offer from date
     * @param string $dateFrom
     * @return $this
     */
    public function setDateFrom(string $dateFrom): OfferInterface
    {
        return $this->setData(OfferInterface::DATE_FROM, $dateFrom);
    }

    /**
     * Getter offer to date
     * @return ?string
     */
    public function getDateTo(): ?string
    {
        return $this->getData(OfferInterface::DATE_TO);
    }

    /**
     * Setter offer to date
     * @param string $dateTo
     * @return $this
     */
    public function setDateTo(string $dateTo): OfferInterface
    {
        return $this->setData(OfferInterface::DATE_TO, $dateTo);
    }
}
