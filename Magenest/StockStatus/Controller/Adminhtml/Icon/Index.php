<?php
namespace Magenest\StockStatus\Controller\Adminhtml\Icon;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Framework\App\Action\Action {
    protected $resultPageFactory;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ){
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    public function execute(){
        $resultPage = $this->resultPageFactory->create();//this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magenest_StockStatus::magenest_stockstatus_icon');
        $resultPage->getConfig()->getTitle()->prepend(__('Icon management'));
        return $resultPage;
    }
    public function _isAllowed(){
        return $this->_authorization->isAllowed('Magenest_StockStatus::magenest_stockstatus_icon');
    }
}