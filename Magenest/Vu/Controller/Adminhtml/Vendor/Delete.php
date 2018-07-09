<?php
namespace Magenest\Vu\Controller\Adminhtml\Vendor;

use    Magento\Backend\App\Action\Context;
use    Magento\Framework\View\Result\PageFactory;

class Delete extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {

        $id = $this->getRequest()->getParam('id');

        $VendorCollection = $this->_objectManager->create('Magenest\Vu\Model\Vendor')->load($id);

        $VendorCollection->delete();

        $this->messageManager->addSuccess(__('You delete Vendor success.'));

        $this->_redirect('vu/vendor');

    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_Vu::vendor');
    }
}