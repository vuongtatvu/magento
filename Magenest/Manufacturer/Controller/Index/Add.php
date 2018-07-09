<?php

namespace Magenest\Manufacturer\Controller\Index;

class Add extends \Magento\Framework\App\Action\Action {

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
    
            $ManufacturerCollection = $this->_objectManager->create('Magenest\Manufacturer\Model\Manufacturer');

            $ManufacturerCollection->setName($name);
            $ManufacturerCollection->setEnabled($enabled);
            $ManufacturerCollection->setAddressStreet($address_street);
            $ManufacturerCollection->setAddressCity($address_city);
            $ManufacturerCollection->setAddressCountry($address_country);
            $ManufacturerCollection->setContactName($contact_name);
            $ManufacturerCollection->setContactPhone($contact_phone);
            $ManufacturerCollection->save();
            
            $this->messageManager->addSuccess(__('You edit manufacturer success.'));

            return $this->_redirect('manufacturer/index/index');
            
        }
        return $this->_redirect('manufacturer/index/index');
    }
}