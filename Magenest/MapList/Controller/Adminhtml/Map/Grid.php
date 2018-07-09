<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 9/27/16
 * Time: 16:39
 */

namespace Magenest\MapList\Controller\Adminhtml\Map;

use Magenest\MapList\Controller\Adminhtml\Map;

class Grid extends Map
{
    /**
     * @return \Magento\Framework\Controller\Result\Raw|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
//        $id    = $this->getRequest()->getParam('location_id');
//        $model = $this->_objectManager->create('Magenest\MapList\Model\Location');
//
//        if ($id) {
//            $model->load($id);
//            if (!$model->getId()) {
//                $this->messageManager->addError(__('This rule no longer exists.'));
//                /*
//                    * \Magento\Backend\Model\View\Result\Redirect $resultRedirect
//                 */
//                $resultRedirect = $this->resultRedirectFactory->create();
//                return $resultRedirect->setPath('*/*/');
//            }
//        }

        //$this->_coreRegistry->register('category', 1);
        $resultRaw = $this->resultRawFactory->create();

        return $resultRaw->setContents(
            $this->layoutFactory->create()->createBlock(
                'Magenest\MapList\Block\Adminhtml\Map\Edit\Tab\AddLocation\Grid',
                'location.tab.list'
            )->toHtml()
        );
    }
}
