<?php

namespace DnD\Offering\Ui\DataProvider\Offer\Form;

use DnD\Offering\Model\Offer;
use DnD\Offering\Model\Offer\FileInfo;
use DnD\Offering\Model\Offer\RedirectUrl;
use DnD\Offering\Api\Data\OfferInterface;
use DnD\Offering\Api\OfferRepositoryInterface;
use DnD\Offering\Model\OfferFactory;
use DnD\Offering\Model\Offer\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;

/**
 * Offering Offer DataProvider
 */
class DataProvider extends ModifierPoolDataProvider
{

    /**
     * @var array
     */
    protected array $loadedData;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $offerCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param PoolInterface $pool
     * @param RequestInterface $request
     * @param OfferRepositoryInterface $offerRepository
     * @param OfferFactory $offerFactory
     * @param FileInfo $fileInfo
     * @param RedirectUrl $redirectUrl
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $offerCollectionFactory,
        protected DataPersistorInterface $dataPersistor,
        protected PoolInterface $pool,
        protected RequestInterface $request,
        protected OfferRepositoryInterface $offerRepository,
        protected OfferFactory $offerFactory,
        protected FileInfo $fileInfo,
        protected RedirectUrl $redirectUrl,
        array $meta = [],
        array $data = [],
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
        $this->collection = $offerCollectionFactory->create();
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    public function getData(): array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $offer = $this->getCurrentOffer();
        $this->loadedData[$offer->getId()] = $this->convertValues($offer);
        return $this->loadedData;
    }

    /**
     * Return current offer
     *
     * @return OfferInterface
     */
    private function getCurrentOffer(): OfferInterface
    {
        $data = $this->dataPersistor->get('offering_offer');
        $offer = $this->offerFactory->create();
        if (!empty($data)) {
            if('' === (string)$data['offer_id']){
                $data['offer_id'] = null;
            }
            $offer->setData($data);
            $this->dataPersistor->clear('offering_offer');
        } else {
            $offerId = $this->getOfferId();
            if ($offerId) {
                try {
                    $offer = $this->offerRepository->getById($offerId);
                } catch (NoSuchEntityException) {
                    //get the default value
                }
            }
        }
        return $offer;
    }

    /**
     * Returns current offer id from request
     *
     * @return int
     */
    private function getOfferId(): int
    {
        return (int) $this->request->getParam($this->getRequestFieldName());
    }

    /**
     * Converts data to acceptable for rendering format
     *
     * @param Offer $offer
     * @return array
     */
    private function convertValues(Offer $offer): array
    {
        $data = $offer->getData();
        $fieldName = OfferInterface::IMAGE_PATH;
        if (isset($data[$fieldName]) && is_string($data[$fieldName])) {
            $fileName = $data[$fieldName];
            if($this->fileInfo->isExist($fileName)) {
                $data[$fieldName] = [];
                $stat = $this->fileInfo->getStat($fileName);
                $mime = $this->fileInfo->getMimeType($fileName);
                $data[$fieldName][0]['name'] = basename($fileName);
                $data[$fieldName][0]['url'] = $this->fileInfo->getUrl($fileName);
                $data[$fieldName][0]['size'] = $stat['size'];
                $data[$fieldName][0]['type'] = $mime;
            }
        }

        if (isset($data[OfferInterface::REDIRECT_URL]) && is_string($data[OfferInterface::REDIRECT_URL])) {
            $this->redirectUrl->decodeRedirectUrlJson($offer);
            $data[OfferInterface::REDIRECT_URL] = $offer->getData(OfferInterface::REDIRECT_URL);
        }

        return $data;
    }
}
