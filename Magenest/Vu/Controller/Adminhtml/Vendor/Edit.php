<?php
namespace Magenest\Vu\Controller\Adminhtml\Vendor;

use    Magento\Backend\App\Action\Context;
use    Magento\Framework\View\Result\PageFactory;

class Edit extends \Magento\Backend\App\Action
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

            $id = $this->getRequest()->getParam('id');

            $customer_id = $this->getRequest()->getParam('customer_id');
            $first_name = $this->getRequest()->getParam('first_name');
            $last_name = $this->getRequest()->getParam('last_name');
            $email = $this->getRequest()->getParam('email');
            $company = $this->getRequest()->getParam('company');
            $phone_number = $this->getRequest()->getParam('phone_number');
            $fax = $this->getRequest()->getParam('fax');
            $address = $this->getRequest()->getParam('address');
            $street = $this->getRequest()->getParam('street');
            $country = $this->getRequest()->getParam('country');
            $city = $this->getRequest()->getParam('city');
            $postcode = $this->getRequest()->getParam('postcode');

            $VendorCollection = $this->_objectManager->create('Magenest\Vu\Model\Vendor')->load($id);

            $VendorCollection->setCustomerId($customer_id);
            $VendorCollection->setFirstName($first_name);
            $VendorCollection->setLastName($last_name);
            $VendorCollection->setEmail($email);
            $VendorCollection->setCompany($company);
            $VendorCollection->setPhoneNumber($phone_number);
            $VendorCollection->setFax($fax);
            $VendorCollection->setAddress($address);
            $VendorCollection->setStreet($street);
            $VendorCollection->setCountry($country);
            $VendorCollection->setCity($city);
            $VendorCollection->setPostcode($postcode);
            $VendorCollection->save();
       
            $this->messageManager->addSuccess(__('You edit movie success.'));

            $this->_redirect('vu/vendor');


        }
        return $resultPage;

    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_Vu::vendor');
    }
}