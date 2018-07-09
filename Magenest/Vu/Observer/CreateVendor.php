<?php

namespace Magenest\Vu\Observer;

use    Magento\Framework\Event\ObserverInterface;

class   CreateVendor implements ObserverInterface
{

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $customer = $observer->getEvent()->getCustomer();

        $VendorCollection = $objectManager->create('Magenest\Vu\Model\Vendor');

        $VendorCollection->setCustomerId($customer->getId());
        $VendorCollection->setFirstName($customer->getFirstName());
        $VendorCollection->setLastName($customer->getLastName());
        $VendorCollection->setEmail($customer->getEmail());
        $VendorCollection->save();

        $customerRepositoryInterface = $objectManager->create('\Magento\Customer\Api\CustomerRepositoryInterface');

        $customer->setCustomAttribute('vu_is_approved', 1);
        $customerRepositoryInterface->save($customer);


    }
}