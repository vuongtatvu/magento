<?php
/**
 * Created by PhpStorm.
 * User: heomep
 * Date: 19/09/2016
 * Time: 13:50
 */

namespace Magenest\MapList\Controller\Adminhtml\Category;

use Magenest\MapList\Controller\Adminhtml\Category;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Category
{
    public function execute()
    {
//        if ($this->getRequest()->getQuery('ajax')) {
//            $resultForward = $this->resultForwardFactory->create();
//            $resultForward->forward('grid');
//            return $resultForward;
//        }

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magenest_MapList::index');
        $resultPage->getConfig()->getTitle()->prepend(__('Tags Management'));
        $resultPage->addBreadcrumb(__('Tags Management'), __('Tags Management'));
        $resultPage->addBreadcrumb(__('Magenest List Tag'), __('Magenest List Tag'));

        return $resultPage;
    }
}
