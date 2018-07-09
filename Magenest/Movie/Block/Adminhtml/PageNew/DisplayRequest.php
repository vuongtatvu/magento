<?php
namespace Magenest\Movie\Block\Adminhtml\PageNew;

use    Magento\Framework\View\Element\Template;

class DisplayRequest extends Template
{
    public function getNumberModules()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->create('Magento\Config\Model\ResourceModel\Config\Data\Collection');
        $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $values = $connection->fetchAll('select * from setup_module ');
        return count($values);
    }

    public function getNumberModulesNotMagento()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->create('Magento\Config\Model\ResourceModel\Config\Data\Collection');
        $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $values = $connection->fetchAll('select * from setup_module WHERE module NOT LIKE "%Magento%"');
        return count($values);
    }

    public function getNumberCustomers()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerCollection = $objectManager->create('\Magento\Customer\Model\ResourceModel\Customer\Collection');
        $collection = $customerCollection->addAttributeToSelect('*')->load();
        return count($collection);
    }

    public function getNumberProducts()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
        $collection = $productCollection->addAttributeToSelect('*')->load();
        return count($collection);
    }

    public function getNumberOrders()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $orderCollection = $objectManager->create('\Magento\Sales\Model\ResourceModel\Order\Collection');
        $collection = $orderCollection->addAttributeToSelect('*')->load();
        return count($collection);
    }

    public function getNumberInvoices()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $orderCollection = $objectManager->create('Magento\Sales\Model\ResourceModel\Order\Invoice\Item\Collection');
        $collection = $orderCollection->addAttributeToSelect('*')->load();
        return count($collection);
    }

    public function getNumberCreditmemos()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $orderCollection = $objectManager->create('Magento\Sales\Model\ResourceModel\Order\Creditmemo\Item\Collection');
        $collection = $orderCollection->addAttributeToSelect('*')->load();
        return count($collection);
    }
}