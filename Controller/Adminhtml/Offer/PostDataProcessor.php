<?php

namespace DnD\Offering\Controller\Adminhtml\Offer;

use DnD\Offering\Api\Data\OfferInterface;
use DnD\Offering\Model\Offer\CollectionFactory;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filter\FilterInput;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Ramsey\Collection\Exception\NoSuchElementException;

/**
 * Controller helper for user input.
 */
class PostDataProcessor
{

    /**
     * @param Date $dateFilter
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        protected Date $dateFilter,
        protected ManagerInterface $messageManager,
        protected CollectionFactory $offerCollectionFactory,
        protected CategoryRepositoryInterface $categoryRepository,
    ) {
    }

    /**
     * Filtering posted data. Converting localized data if needed
     *
     * @param array $data
     * @return array
     */
    public function filter(array $data): array
    {
        $filterRules = [];
        foreach (['date_from', 'date_to'] as $dateField) {
            if (!empty($data[$dateField])) {
                $filterRules[$dateField] = $this->dateFilter;
            }
        }

        return (new FilterInput($filterRules, [], $data))->getUnescaped();
    }

    /**
     * Check if required fields is not empty
     *
     * @param array $data
     * @return bool
     */
    public function validateRequireEntry(array $data): bool
    {
        $requiredFields = [
            'title' => __('Offer Title'),
            'label' => __('Offer Label'),
            'date_to' => __('Offer Start Date'),
            'date_from' => __('Offer End Date'),
        ];
        $errorNo = true;
        foreach ($data as $field => $value) {
            if (in_array($field, array_keys($requiredFields)) && $value == '') {
                $errorNo = false;
                $this->messageManager->addErrorMessage(
                    __('To apply changes you should fill in hidden required "%1" field', $requiredFields[$field])
                );
            }
        }
        return $errorNo;
    }

}
