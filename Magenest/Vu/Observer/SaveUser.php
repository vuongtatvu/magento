<?php

namespace Magenest\Vu\Observer;

use   Magento\Framework\Event\ObserverInterface;

class    SaveUser implements ObserverInterface
{
    /**    @var    \Psr\Log\LoggerInterface $logger */


    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $user = $observer->getEvent()->getUser();
        $user->getEmail();
        $vendor = $objectManager->create('Magenest\Vu\Model\Vendor')->load('email', $user->getEmail());
        $vendor->getId();
        $vendor->delete();

    }
}