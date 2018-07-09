<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 9/10/16
 * Time: 09:15
 */

namespace Magenest\MapList\Controller\Adminhtml\Location;

use Magenest\MapList\Controller\Adminhtml\Location;

/**
 * Class Index
 * @package Magenest\MapList\Controller\Adminhtml\Location
 */
class Index extends Location
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magenest_MapList::index');
        $resultPage->getConfig()->getTitle()->prepend(__('Location Details'));
        $resultPage->addBreadcrumb(__('Location Details'), __('Location Details'));
        $resultPage->addBreadcrumb(__('Magenest Location Details'), __('Magenest Location Details'));

        return $resultPage;
    }
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_MapList::list_location');
    }
}
