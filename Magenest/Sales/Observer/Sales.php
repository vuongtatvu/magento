<?php

namespace Magenest\Sales\Observer;

use    Magento\Framework\Event\ObserverInterface;

class  Sales implements ObserverInterface
{
    protected $_objectManager;

    protected $custsession;

    protected $_customerRepositoryInterface;

    public function __construct(
        \Magento\Customer\Model\Session $custsession,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        \Magento\Framework\ObjectManagerInterface $objectmanager
    )
    {
        $this->_objectManager = $objectmanager;
        $this->custsession = $custsession;
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        $customerId = $this->custsession->getCustomerId();
        $customer = $this->_customerRepositoryInterface->getById($customerId);

        $isSalesAgent = $customer->getCustomAttribute('is_sales_agent')->getValue();

        $order = $observer->getEvent()->getOrder();

        if ($order->getId()) {
            foreach ($order->getAllVisibleItems() as $item) {

                $product_id = $item->getProductId();

                $productData = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($product_id);

                $price = $productData->getData('price');
                $commissionType = $productData->getData('commission_type');
                $sku = $productData->getData('sku');
                $commissionValue = $productData->getData('commission_value');
                $sale_agent_id = $productData->getData('sale_agent_id');

                $commission = $this->_objectManager->create('\Magenest\Sales\Model\MagenestSales');
                if (($isSalesAgent) && $customerId == $sale_agent_id) {
                    $commission->setCustomerId($customerId);
                    $commission->setOrderId($order->getId());
                    $commission->setOrderItemId($product_id);
                    $commission->setOrderItemSku($sku);
                    $commission->setOrderItemPrice($price);
                    $commission->setCommisionPercent($commissionType == 'percent' ? $commissionValue : '');
                    $commission->setCommisionValue($commissionType == 'percent' ? $commissionValue * $price / 100 : $commissionValue);
                    $commission->save();
                }
            }
        }
    }
}