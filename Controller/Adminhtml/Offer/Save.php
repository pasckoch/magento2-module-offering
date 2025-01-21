<?php

namespace DnD\Offering\Controller\Adminhtml\Offer;

use DnD\Offering\Api\OfferRepositoryInterface;
use DnD\Offering\Api\Data\OfferInterface;
use DnD\Offering\Model\OfferFactory;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Save offer action.
 *
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'DnD_Offering::save';

    /**
     * @param Action\Context $context
     * @param PostDataProcessor $dataProcessor
     * @param DataPersistorInterface $dataPersistor
     * @param OfferFactory $offerFactory
     * @param OfferRepositoryInterface $offerRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        Action\Context $context,
        protected PostDataProcessor $dataProcessor,
        protected DataPersistorInterface $dataPersistor,
        protected OfferFactory $offerFactory,
        protected OfferRepositoryInterface $offerRepository,
        protected LoggerInterface $logger,
    ) {
        parent::__construct($context);
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data = $this->getRequest()->getPostValue()) {
            $originalData = $data;
            $id = $this->getRequest()->getParam(OfferInterface::OFFER_ID);
            try {
                if(!$this->dataProcessor->validateRequireEntry($data)){
                    throw new LocalizedException(__('Invalid data'));
                }

                $data = $this->dataProcessor->filter($data);
                if ((string)$data[OfferInterface::OFFER_ID] === '') {
                    $data['offer_id'] = null;
                }
                if(!isset($data[OfferInterface::IMAGE_PATH])) {
                    $data[OfferInterface::IMAGE_PATH] = null;
                }
                /** @var OfferInterface $model */
                $model = $id ? $this->offerRepository->getById($id) : $this->offerFactory->create();
                $model->setData($data);
                $this->_eventManager->dispatch('offering_offer_prepare_save',
                    ['offer' => $model, 'request' => $this->getRequest()]);
                $this->offerRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the offer.'));
                return $this->processResultRedirect($model, $resultRedirect, $data);
            } catch (NoSuchEntityException) {
                $this->messageManager->addErrorMessage(__('This offer no longer exists.'));
            } catch (LocalizedException $e) {
                if ($e->getPrevious()) {
                    $this->messageManager->addExceptionMessage($e->getPrevious());
                }
            } catch (Throwable $e) {
                $this->logger->error('Unable save offer', ['exception' => $e]);
                $this->messageManager->addErrorMessage(__('Something went wrong while saving the offer.'));
            }

            $this->dataPersistor->set('offering_offer', $originalData);
            return $resultRedirect->setPath('*/*/edit', ['offer_id' => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param OfferInterface $model
     * @param $resultRedirect
     * @param $data
     * @return mixed
     * @throws CouldNotSaveException
     */
    private function processResultRedirect(OfferInterface $model, $resultRedirect, $data): mixed
    {
        if ($this->getRequest()->getParam('back', false) === 'duplicate') {
            $newOffer = $this->offerFactory->create(['data' => $data]);
            $newOffer->setId(null);
            $this->offerRepository->save($newOffer);
            $this->messageManager->addSuccessMessage(__('You duplicated the offer.'));
            return $resultRedirect->setPath(
                '*/*/edit',
                [
                    'offer_id' => $newOffer->getId(),
                    '_current' => true,
                ]
            );
        }
        $this->dataPersistor->clear('offering_offer');
        if ($this->getRequest()->getParam('back')) {
            return $resultRedirect->setPath('*/*/edit', ['offer_id' => $model->getId(), '_current' => true]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
