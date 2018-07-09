<?php
/**
 * Created by PhpStorm.
 * User: heomep
 * Date: 19/09/2016
 * Time: 13:52
 */

namespace Magenest\MapList\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;

abstract class Category extends Action
{
    protected $coreRegistry;
    protected $resultPageFactory;
    protected $categoryFactory;
    protected $logger;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        Registry $coreRegistry,
        \Magenest\MapList\Model\CategoryFactory $categoryFactory,
        LoggerInterface $logger
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        $this->categoryFactory = $categoryFactory;
        $this->logger = $logger;
        parent::__construct($context); // TODO: Change the autogenerated stub
    }

    protected function _initAction()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Tag Management'));

        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_MapList::category');
    }
}
