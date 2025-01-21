<?php

namespace PascKoch\Offering\Block\Adminhtml\Offer\Edit;

use Magento\Backend\Block\Widget\Context;
use PascKoch\Offering\Api\OfferRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class GenericButton
 */
class GenericButton
{
    /**
     * @var Context
     */
    protected Context $context;

    /** @var OfferRepositoryInterface  */
    protected OfferRepositoryInterface $offerRepository;

    /**
     * @param Context $context
     * @param OfferRepositoryInterface $offerRepository
     */
    public function __construct(
        Context $context,
        OfferRepositoryInterface $offerRepository
    ) {
        $this->context = $context;
        $this->offerRepository = $offerRepository;
    }

    /**
     * @return int|null
     */
    public function getOfferId(): ?int
    {
        try {
            return $this->offerRepository->getById((int)$this->context->getRequest()->getParam('offer_id'))->getId();
        } catch (NoSuchEntityException) {
            //
        }
        return null;
    }

    /**
     * Generate url by route and parameters
     *
     * @param string $route
     * @param array $params
     * @return  string
     */
    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
