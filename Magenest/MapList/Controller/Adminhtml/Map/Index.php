<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 9/10/16
 * Time: 09:15
 */

namespace Magenest\MapList\Controller\Adminhtml\Map;

use Magenest\MapList\Controller\Adminhtml\Map;

/**
 * Class Index
 * @package Magenest\MapList\Controller\Adminhtml\Map
 */
class Index extends Map
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magenest_MapList::index');
        $resultPage->getConfig()->getTitle()->prepend(__('Map List'));
        $resultPage->addBreadcrumb(__('Map List'), __('Map List'));
        $resultPage->addBreadcrumb(__('Magenest Map List'), __('Magenest Map List'));

        return $resultPage;
    }
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_MapList::list_map');
    }
}
