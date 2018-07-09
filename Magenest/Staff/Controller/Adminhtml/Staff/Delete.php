<?php

namespace Magenest\Staff\Controller\Adminhtml\Staff;

use Magento\Framework\Controller\ResultFactory;

class Delete extends \Magento\Backend\App\Action
{

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $ids = $this->getRequest()->getParam('selected');
        $id = $this->getRequest()->getParam('id');
        if ($ids) {
            foreach ($ids as $id) {
                $this->_objectManager->create('Magenest\Staff\Model\Staff')
                    ->load($id)->delete();
            }
            $this->messageManager->addSuccess(
                __('You removed from the list.')
            );
        } else if ($id) {
            $this->_objectManager->create('Magenest\Staff\Model\Staff')
                ->load($id)->delete();
        }
        $this->_redirect('staff/staff');
    }
}