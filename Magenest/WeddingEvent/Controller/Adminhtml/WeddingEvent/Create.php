<?php

namespace Magenest\WeddingEvent\Controller\Adminhtml\WeddingEvent;

use Magento\Framework\Controller\ResultFactory;

class Create extends \Magento\Backend\App\Action
{

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {


        if ($_POST) {
            
            $title = $this->getRequest()->getParam('title');
            $commission = $this->getRequest()->getParam('commission');
            $bride_firstname = $this->getRequest()->getParam('bride_firstname');
            $bride_lastname = $this->getRequest()->getParam('bride_lastname');
            $bride_email = $this->getRequest()->getParam('bride_email');
            $sent_to_bride = $this->getRequest()->getParam('sent_to_bride');
            $groom_firstname = $this->getRequest()->getParam('groom_firstname');
            $groom_lastname = $this->getRequest()->getParam('groom_lastname');
            $groom_email = $this->getRequest()->getParam('groom_email');
            $sent_to_groom = $this->getRequest()->getParam('sent_to_groom');
            $message = $this->getRequest()->getParam('message');

            $wedding = $this->_objectManager->create('Magenest\WeddingEvent\Model\WeddingEvent');
            
            $wedding->setTitle($title);
            $wedding->setCommission($commission);
            $wedding->setBrideFirstname($bride_firstname);
            $wedding->setBrideLastname($bride_lastname);
            $wedding->setBrideEmail($bride_email);
            $wedding->setSentToBride($sent_to_bride);
            $wedding->setGroomFirstname($groom_firstname);
            $wedding->setGroomLastname($groom_lastname);
            $wedding->setGroomEmail($groom_email);
            $wedding->setSentToGroom($sent_to_groom);
            $wedding->setMessage($message);

            $wedding->save();

            $resultRedirect = $this->resultRedirectFactory->create();
            $this->messageManager->addSuccess(__('You create wedding success.'));
            
            $this->_eventManager->dispatch('create_virtual_product', ['change' => $wedding]);

            return $resultRedirect->setPath('weddingevent/weddingevent');
        }


        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        return $resultPage;
    }
}