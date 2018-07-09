<?php

namespace Magenest\Staff\Controller\Adminhtml\Staff;

use Magento\Framework\Controller\ResultFactory;

class Status extends \Magento\Backend\App\Action
{

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $ids = $this->getRequest()->getParam('selected');
        $id = $this->getRequest()->getParam('id');
        $status = $this->getRequest()->getParam('status');
        if ($ids) {
            foreach ($ids as $id) {
                $staff = $this->_objectManager->create('Magenest\Staff\Model\Staff')->load($id);
                $staff->setStatus($status);
                $staff->save();
            }
            $this->messageManager->addSuccess(
                __('You change from the list.')
            );
        } else if ($id) {
            $staff = $this->_objectManager->create('Magenest\Staff\Model\Staff')->load($id);
            $staff->setStatus($status);
            $staff->save();
        }
        $this->_redirect('staff/staff');
    }
}