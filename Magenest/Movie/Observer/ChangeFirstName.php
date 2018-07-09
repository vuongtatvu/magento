<?php

namespace Magenest\Movie\Observer;

use    Magento\Framework\Event\ObserverInterface;

class   ChangeFirstName implements ObserverInterface
{

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerRepositoryInterface = $objectManager->create('\Magento\Customer\Api\CustomerRepositoryInterface');

        $customer = $observer->getEvent()->getCustomer();
        $customer->setFirstName('Magenest');
        $customerRepositoryInterface->save($customer);
    }
}