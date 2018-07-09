<?php

namespace Magenest\Manufacturer\Controller\Index;

class    Show extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {

        $id = $this->getRequest()->getParam('id');

        $manufacturer = $this->_objectManager->create('Magenest\Manufacturer\Model\Manufacturer')->load($id);

        echo 'Name: ' . $manufacturer->getName() . "\n " . 'Street Adree: ' . $manufacturer->getAddressStreet() . "\n " .
            'City: ' . $manufacturer->getAddressCity() . "\n " .
            'Country: ' . $manufacturer->getAddressCountry() . "\n " .
            'Phone: ' . $manufacturer->getContactPhone() . "\n ";
    }
}