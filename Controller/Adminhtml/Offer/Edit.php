<?php

namespace PascKoch\Offering\Controller\Adminhtml\Offer;

use PascKoch\Offering\Api\OfferRepositoryInterface;
use PascKoch\Offering\Model\OfferFactory;
use PascKoch\Offering\Model\OfferRepository;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'PascKoch_Offering::save';

    /**
     * Core registry
     *
     * @var Registry
     */
    protected Registry $coreRegistry;

    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;

    /**
     * @var OfferRepository
     */
    protected OfferRepository $offerRepository;

    /**
     * @var OfferFactory
     */
    protected OfferFactory $offerFactory;

    /**
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param Registry $registry
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        Registry $registry,
        OfferFactory $offerFactory,
        OfferRepositoryInterface $offerRepository
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $registry;
        $this->offerFactory = $offerFactory;
        $this->offerRepository = $offerRepository;
        parent::__construct($context);
    }

    /**
     * Init actions
     *
     * @return Page
     */
    protected function _initAction(): Page
    {
        // load layout, set active menu and breadcrumbs
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('PascKoch_Offering::offering_offers')
            ->addBreadcrumb(__('Offers'), __('Offers'))
            ->addBreadcrumb(__('Manage Offers'), __('Manage Offers'));
        return $resultPage;
    }

    /**
     * Edit offer
     *
     * @return Page|Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('offer_id');
        if ($id) {
            try {
                $model = $this->offerRepository->getById($id);
            } catch (NoSuchEntityException) {
                $this->messageManager->addErrorMessage(__('This offer no longer exists.'));
                return $this->resultRedirectFactory->create()->setPath('*/*/');
            }
        } else {
            $model = $this->offerFactory->create();
        }
        $this->coreRegistry->register('offering_offer', $model);

        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Offer') : __('New Offer'),
            $id ? __('Edit Offer') : __('New Offer')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Offers'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('New Offer'));

        return $resultPage;
    }
}
