<?php
namespace Magenest\Manufacturer\Controller\Adminhtml\Manufacturer;

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

        $entity_id = $this->getRequest()->getParam('entity_id');

        $ManufacturerCollection = $this->_objectManager->create('Magenest\Manufacturer\Model\Manufacturer')->load($entity_id);

        $ManufacturerCollection->delete();

        $this->messageManager->addSuccess(__('You delete Manufacturer success.'));

        $this->_redirect('manufacturer/manufacturer');

    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_Manufacturer::manufacturer');
    }
}