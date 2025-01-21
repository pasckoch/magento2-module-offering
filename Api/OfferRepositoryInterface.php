<?php

namespace PascKoch\Offering\Api;

use PascKoch\Offering\Api\Data\OfferInterface;
use PascKoch\Offering\Api\Data\OfferSearchResultsInterface;
use Magento\Catalog\Model\Category;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\Store;

interface OfferRepositoryInterface
{
    /**
     * @param int $id
     * @return OfferInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): OfferInterface;

    /**
     * @param OfferInterface $offer
     * @return OfferInterface
     * @throws CouldNotSaveException
     */
    public function save(OfferInterface $offer): OfferInterface;

    /**
     * @param OfferInterface $offer
     * @return void
     * @throws CouldNotDeleteException
     */
    public function delete(OfferInterface $offer): void;

    /**
     * @param Category|int|array|null $categoryId
     * @param Store|int|array|null $storeId
     * @param SearchCriteriaInterface|null $searchCriteria
     * @return OfferSearchResultsInterface
     */
    public function getList(
        Category|int|array $categoryId = null,
        Store|int|array  $storeId = null,
        SearchCriteriaInterface $searchCriteria = null
    ): OfferSearchResultsInterface;
}
