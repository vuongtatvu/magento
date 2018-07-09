<?php

namespace Magenest\Staff\Controller\Staff;

class Update extends \Magento\Framework\App\Action\Action {

    public function __construct(
        \Magento\Framework\App\Action\Context $context
    ){
        parent::__construct($context);
    }

    public function execute()
    {
        
        if ($_POST) {
            
            $name = $this->getRequest()->getParam('name');
            $enabled = $this->getRequest()->getParam('enabled');
            $address_street = $this->getRequest()->getParam('address_street');
            $address_city = $this->getRequest()->getParam('address_city');
            $address_country = $this->getRequest()->getParam('address_country');
            $contact_name = $this->getRequest()->getParam('contact_name');
            $contact_phone = $this->getRequest()->getParam('contact_phone');

            if($enabled != 1){
                $enabled = 0;
            }
    
            $StaffCollection = $this->_objectManager->create('Magenest\Staff\Model\Staff');

            $StaffCollection->setName($name);
            $StaffCollection->setEnabled($enabled);
            $StaffCollection->setAddressStreet($address_street);
            $StaffCollection->setAddressCity($address_city);
            $StaffCollection->setAddressCountry($address_country);
            $StaffCollection->setContactName($contact_name);
            $StaffCollection->setContactPhone($contact_phone);
            $StaffCollection->save();
            
            $this->messageManager->addSuccess(__('You edit Staff success.'));

            return $this->_redirect('Staff/index/index');
            
        }
        return $this->_redirect('Staff/index/index');
    }
}