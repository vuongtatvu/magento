<?php
namespace Magenest\PartTime\Controller\Adminhtml\PartTime;

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

        $member_id = $this->getRequest()->getParam('member_id');

        $parttimeCollection = $this->_objectManager->create('Magenest\PartTime\Model\PartTime')->load($member_id);

        $parttimeCollection->delete();

        $this->messageManager->addSuccess(__('You delete parttime success.'));

        $this->_redirect('parttime/parttime');

    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_PartTime::parttime');
    }
}