<?php

namespace Magenest\PartTime\Observer;

use    Magento\Framework\Event\ObserverInterface;

class   CreateMember implements ObserverInterface
{

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $customer = $observer->getEvent()->getCustomer();

        $parttimeCollection = $objectManager->create('Magenest\PartTime\Model\PartTime');

        $parttimeCollection->setName($customer->getFirstName().' '.$customer->getLastname());
        $parttimeCollection->setAddress($customer->getEmail());
        $parttimeCollection->setPhone('111111111');
        $parttimeCollection->save();

    }
}