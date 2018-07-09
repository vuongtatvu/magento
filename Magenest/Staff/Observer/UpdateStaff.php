<?php

namespace Magenest\Staff\Observer;

use    Magento\Framework\Event\ObserverInterface;

class   UpdateStaff implements ObserverInterface
{
    
    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        $customer = $observer->getEvent()->getCustomer();

        $type =$customer->getCustomattribute('staff_type');

        $id = $customer->getId();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $staffCollection = $objectManager->create('\Magenest\Staff\Model\ResourceModel\Staff\Collection');
        $staffcc = $staffCollection->addFieldToFilter('customer_id', $id)->load();

        $a = $staffcc->getData();

        if ($a != null) {
            $staffId = $a[0]['id'];

            if ($staffId != null) {
                $staff = $objectManager->create('Magenest\Staff\Model\Staff')->load($staffId);
                $staff->setCustomerId($id);
                $staff->setNickName($customer->getFirstName()." ".$customer->getLastName());
                $staff->setType($type->getValue());
                $staff->setStatus(2);
                $staff->save();
            } else {
                $staff = $objectManager->create('Magenest\Staff\Model\Staff');
                $staff->setCustomerId($id);
                $staff->setNickName($customer->getFirstName()." ".$customer->getLastName());
                $staff->setType($type->getValue());
                $staff->setStatus(2);
                $staff->save();
            }
        } else {
            $staff = $objectManager->create('Magenest\Staff\Model\Staff');
            $staff->setCustomerId($id);
            $staff->setNickName($customer->getFirstName()." ".$customer->getLastName());
            $staff->setType($type->getValue());
            $staff->setStatus(2);
            $staff->save();
        }
    }
}