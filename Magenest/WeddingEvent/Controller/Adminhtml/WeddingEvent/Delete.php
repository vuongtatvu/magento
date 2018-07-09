<?php

namespace Magenest\WeddingEvent\Controller\Adminhtml\WeddingEvent;

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
        $a = $this->getRequest()->getParams();
        $b = 1;
        if ($ids) {
            foreach ($ids as $id) {
                $this->_objectManager->create('Magenest\WeddingEvent\Model\WeddingEvent')->load($id)->delete();
            }
            $this->messageManager->addSuccess(__('You removed employee from the list.')
            );
        } else if ($id) {
            $this->_objectManager->create('Magenest\WeddingEvent\Model\WeddingEvent')->load($id)->delete();
        }
        $this->_redirect('weddingevent/weddingevent');
    }
}