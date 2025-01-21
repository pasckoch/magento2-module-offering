<?php

namespace DnD\Offering\Ui\Component\Category;

use Magento\Backend\Model\Auth\Session;
use Magento\Catalog\Model\Category as CategoryModel;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\App\Cache\Type\Block;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;

class Options implements OptionSourceInterface
{
    public const ALL_STORE_VIEWS = '0';

    public const CATEGORY_TREE_ID = 'OFFERING_OFFER_CATEGORY_TREE';

    /**
     * @param CollectionFactory $categoryCollectionFactory
     * @param Session $session
     * @param CacheInterface $cache
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     */
    public function __construct(
        protected CollectionFactory $categoryCollectionFactory,
        protected Session $session,
        protected CacheInterface $cache,
        protected SerializerInterface $serializer,
        protected LoggerInterface $logger
    ) {
    }

    /**
     * @inheritDoc
     */
    public function toOptionArray(): ?array
    {
        return $this->getCategoriesTree();
    }

    /**
     * @return mixed
     */
    protected function getCategoriesTree(): mixed
    {
        $cachedCategoriesTree = $this->cache->load($this->getCategoriesTreeCacheId());
        if (!empty($cachedCategoriesTree)) {
            return $this->serializer->unserialize($cachedCategoriesTree);
        }

        $categoryById = [
            CategoryModel::TREE_ROOT_ID => [
                'value' => CategoryModel::TREE_ROOT_ID,
                'optgroup' => null,
            ],
        ];
        try {
            $categoryCollection = $this->categoryCollectionFactory->create();
            $categoryCollection->addAttributeToFilter('entity_id',
                ['in' => array_keys($this->retrieveShownCategoriesIds())])
                ->addAttributeToSelect(['name', 'is_active', 'parent_id'])
                ->setStoreId(static::ALL_STORE_VIEWS);

            foreach ($categoryCollection as $category) {
                foreach ([$category->getId(), $category->getParentId()] as $categoryId) {
                    if (!isset($categoryById[$categoryId])) {
                        $categoryById[$categoryId] = ['value' => $categoryId];
                    }
                }
                $categoryById[$category->getId()]['is_active'] = $category->getIsActive();
                $categoryById[$category->getId()]['label'] = $category->getName();
                $categoryById[$category->getId()]['__disableTmpl'] = true;
                $categoryById[$category->getParentId()]['optgroup'][] = &$categoryById[$category->getId()];
            }

            $this->cache->save(
                $this->serializer->serialize($categoryById[CategoryModel::TREE_ROOT_ID]['optgroup']),
                $this->getCategoriesTreeCacheId(),
                [
                    CategoryModel::CACHE_TAG,
                    Block::CACHE_TAG
                ]
            );
        } catch (LocalizedException $e) {
            $this->logger->error('Unable to tree showing category collection', ['exception' => $e]);
        }

        return $categoryById[CategoryModel::TREE_ROOT_ID]['optgroup'];
    }

    /**
     * Get cache id for categories tree.
     *
     * @return string
     */
    private function getCategoriesTreeCacheId(): string
    {
        if ($this->session->getUser() !== null) {
            return self::CATEGORY_TREE_ID . '_' . $this->session->getUser()->getAclRole();
        }
        return self::CATEGORY_TREE_ID;
    }

    /**
     * @return array
     */
    protected function retrieveShownCategoriesIds(): array
    {
        $shownCategoryIds = [];
        try {
            $categoryCollection = $this->categoryCollectionFactory->create();
            $matchingNamesCollection = $categoryCollection
                ->addAttributeToSelect('path')
                ->addAttributeToFilter('entity_id', ['neq' => CategoryModel::TREE_ROOT_ID])
                ->setStoreId(static::ALL_STORE_VIEWS);
            /** @var CategoryModel $category */
            foreach ($matchingNamesCollection as $category) {
                foreach (explode('/', $category->getPath() ?? '') as $parentId) {
                    $shownCategoryIds[$parentId] = 1;
                }
            }
        } catch (LocalizedException $e) {
            $this->logger->error('Unable to filter showing category collection', ['exception' => $e]);
        }
        return $shownCategoryIds;
    }
}
