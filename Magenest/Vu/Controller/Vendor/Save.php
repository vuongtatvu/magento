<?php

namespace Magenest\Vu\Controller\Vendor;

use Magento\Framework\Controller\ResultFactory;

class Save extends \Magento\Framework\App\Action\Action
{

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {


        if ($_POST) {

            $id = $this->getRequest()->getParam('id');
            $first_name = $this->getRequest()->getParam('first_name');
            $last_name = $this->getRequest()->getParam('last_name');
            $email = $this->getRequest()->getParam('email');
            $company = $this->getRequest()->getParam('company');
            $phone_number = $this->getRequest()->getParam('phone_number');
            $fax = $this->getRequest()->getParam('fax');
            $address = $this->getRequest()->getParam('address');
            $street = $this->getRequest()->getParam('street');
            $city = $this->getRequest()->getParam('city');
            $post_code = $this->getRequest()->getParam('postcode');
            $country = $this->getRequest()->getParam('country');

            $vendor = $this->_objectManager->create('Magenest\Vu\Model\Vendor')->load($id);

            $vendor->setFirstName($first_name);
            $vendor->setLastName($last_name);
            $vendor->setEmail($email);
            $vendor->setCompany($company);
            $vendor->setPhoneNumber($phone_number);
            $vendor->setFax($fax);
            $vendor->setAdress($address);
            $vendor->setStreet($street);
            $vendor->setCity($city);
            $vendor->setPostcode($post_code);
            $vendor->setCountry($country);
            $vendor->save();

            $resultRedirect = $this->resultRedirectFactory->create();
            $this->messageManager->addSuccess(__('You updated customer success.'));
            return $resultRedirect->setPath('vu/vendor/');
        }


        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        return $resultPage;
    }
}