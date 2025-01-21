<?php

namespace DnD\Offering\Model;

use DnD\Offering\Api\OfferRepositoryInterface;
use DnD\Offering\Api\Data\OfferInterface;
use DnD\Offering\Api\Data\OfferSearchResultsInterface;
use DnD\Offering\Api\Data\OfferSearchResultsInterfaceFactory;
use DnD\Offering\Model\Offer\ResourceModel\Offer;
use DnD\Offering\Model\Offer\CollectionFactory;
use Exception;
use Magento\Catalog\Model\Category;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

class OfferRepository implements OfferRepositoryInterface
{
    /**
     * @param OfferFactory $offerFactory
     * @param Offer $offerResource
     * @param CollectionFactory $offerCollectionFactory
     * @param OfferSearchResultsInterfaceFactory $offerSearchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     * @param StoreManagerInterface $storeManager
     * @param TimezoneInterface $timezone
     */
    public function __construct(
        protected OfferFactory $offerFactory,
        protected Offer $offerResource,
        protected CollectionFactory $offerCollectionFactory,
        protected OfferSearchResultsInterfaceFactory $offerSearchResultsFactory,
        protected CollectionProcessorInterface $collectionProcessor,
        protected SearchCriteriaBuilder $searchCriteriaBuilder,
        protected SortOrderBuilder $sortOrderBuilder,
        protected StoreManagerInterface $storeManager,
        protected TimezoneInterface $timezone
    ) {
    }

    /**
     * @param int $id
     * @return OfferInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): OfferInterface
    {
        $offer = $this->offerFactory->create();
        try {
            $this->offerResource->load($offer, $id);
            if (!$offer->getId()) {
                throw new LocalizedException(__('Offer not found.'));
            }
        } catch (LocalizedException) {
            throw new NoSuchEntityException(__('Unable to find offer with ID "%1"', $id));
        }
        return $offer;
    }

    /**
     * @param OfferInterface $offer
     * @return OfferInterface
     * @throws CouldNotSaveException
     */
    public function save(OfferInterface $offer): OfferInterface
    {
        try {
            $offer->setStoreId($offer->getStoreId() ?? $this->storeManager->getStore()->getId());
            $this->offerResource->save($offer);
        } catch (AlreadyExistsException|NoSuchEntityException|Exception) {
            throw new CouldNotSaveException(__('Unable to save offer'));
        }
        return $offer;
    }

    /**
     * @param OfferInterface $offer
     * @return void
     * @throws CouldNotDeleteException
     */
    public function delete(OfferInterface $offer): void
    {
        try {
            $this->offerResource->delete($offer);
        } catch (Exception) {
            throw new CouldNotDeleteException(__('Unable to delete offer'));
        }
    }

    /**
     * @param Category|int|array|null $categoryId
     * @param Store|int|array|null $storeId
     * @param SearchCriteriaInterface|null $searchCriteria
     * @return OfferSearchResultsInterface
     */
    public function getList(
        Category|int|array $categoryId = null,
        Store|int|array $storeId = null,
        SearchCriteriaInterface $searchCriteria = null
    ): OfferSearchResultsInterface {
        $collection = $this->offerCollectionFactory->create();

        if ($categoryId) {
            $collection->addCategoryFilter($categoryId);
        }

        if ($storeId) {
            $collection->addStoreFilter($storeId);
        }

        if (!$searchCriteria) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
        }
        $this->collectionProcessor->process($searchCriteria, $collection);
        $collection->load();

        $searchResults = $this->offerSearchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        return $searchResults;
    }

    public function getListForToday(Category|int|array $categoryId, Store|int|array $storeId): OfferSearchResultsInterface
    {
        $today = $this->timezone->date()->format('Y-m-d');
        $this->searchCriteriaBuilder->addFilter('date_from', $today, 'lteq');
        $this->searchCriteriaBuilder->addFilter('date_to', $today, 'gteq');
        return $this->getList($categoryId, $storeId, $this->searchCriteriaBuilder->create());
    }

}
