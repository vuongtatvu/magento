<?php
namespace Magenest\StockStatus\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Framework\App\Action\Action {
    protected $resultPageFacetory;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ){
        parent::__construct($context);
        $this->resultPageFacetory = $resultPageFactory;
    }
    public function execute(){
        $resultPage = $this->resultPageFacetory->create();//this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magenest_StockStatus::magenest_qtyrule');
        $resultPage->getConfig()->getTitle()->prepend(__('Custom Stock Status'));
        return $resultPage;
    }
    public function _isAllowed(){
        return $this->_authorization->isAllowed('Magenest_StockStatus::magenest_qtyrule');
    }
}