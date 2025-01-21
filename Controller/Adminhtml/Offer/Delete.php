<?php

namespace PascKoch\Offering\Controller\Adminhtml\Offer;

use PascKoch\Offering\Api\OfferRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Delete offer action.
 */
class Delete extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'PascKoch_Offering::offer_delete';

    public function __construct(
        Action\Context $context,
        private readonly OfferRepositoryInterface $offerRepository
    ) {
        parent::__construct($context);
    }

    /**
     * Delete action
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('offer_id');
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            $title = "";
            try {
                $offer = $this->offerRepository->getById($id);
                $this->offerRepository->delete($offer);

                $this->messageManager->addSuccessMessage(__('The offer has been deleted.'));

                $this->_eventManager->dispatch('adminhtml_offer_on_delete', [
                    'title' => $title,
                    'status' => 'success'
                ]);

                return $resultRedirect->setPath('*/*/');
            } catch (CouldNotDeleteException $e) {
                $this->_eventManager->dispatch('adminhtml_offer_on_delete', [
                    'title' => $title,
                    'status' => 'fail'
                ]);
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['offer_id' => $id]);
            }
        }

        $this->messageManager->addErrorMessage(__('We can\'t find a offer to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
