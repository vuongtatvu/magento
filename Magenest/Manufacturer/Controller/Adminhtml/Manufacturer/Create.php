<?php
namespace Magenest\Manufacturer\Controller\Adminhtml\Manufacturer;

use    Magento\Backend\App\Action\Context;
use    Magento\Framework\View\Result\PageFactory;

class Create extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        

        if ($_POST) {

            $name = $this->getRequest()->getParam('name');
            $enabled = $this->getRequest()->getParam('enabled');
            $address_street = $this->getRequest()->getParam('address_street');
            $address_city = $this->getRequest()->getParam('address_city');
            $address_country = $this->getRequest()->getParam('address_country');
            $contact_name = $this->getRequest()->getParam('contact_name');
            $contact_phone = $this->getRequest()->getParam('contact_phone');

           


            $ManufacturerCollection = $this->_objectManager->create('Magenest\Manufacturer\Model\Manufacturer');

            $ManufacturerCollection->setName($name);
            $ManufacturerCollection->setEnable($enabled);
            $ManufacturerCollection->setAddressStreet($address_street);
            $ManufacturerCollection->setAddressCity($address_city);
            $ManufacturerCollection->setAddressCountry($address_country);
            $ManufacturerCollection->setContactName($contact_name);
            $ManufacturerCollection->setContactPhone($contact_phone);
            $ManufacturerCollection->save();
            
            $this->messageManager->addSuccess(__('You create manufacturer success.'));

            $this->_redirect('manufacturer/manufacturer');


        }
        return $resultPage;

    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_Manufacturer::manufacturer');
    }
}