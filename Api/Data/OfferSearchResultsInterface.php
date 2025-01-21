<?php

namespace PascKoch\Offering\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\Framework\Api\SearchResultsInterface;

interface OfferSearchResultsInterface extends SearchResultsInterface
{
    /**
     *
     * @return ExtensibleDataInterface[]
     */
    public function getItems();

    /**
     * Set items list.
     *
     * @param ExtensibleDataInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
