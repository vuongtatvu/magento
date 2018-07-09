<?php

namespace Magenest\Vu\Observer;

use   Magento\Framework\Event\ObserverInterface;

class   MakeOrder implements ObserverInterface
{
    /**    @var    \Psr\Log\LoggerInterface $logger */
    private $_objectManager;
    protected $_order;

    public function __construct(
        \Magento\Sales\Api\Data\OrderInterface $order,
        \Magento\Framework\ObjectManagerInterface $objectmanager
    )
    {
        $this->_objectManager = $objectmanager;
        $this->_order = $order;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $orderId = $observer->getEvent()->getOrderIds();
        $order = $this->_order->load($orderId);
        if ($order->getStatus() == 'Processing') {

            $orderItems = $order->getAllItems();

            foreach ($orderItems as $product) {

                $product_id = $product->getProductId();

                $productData = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($product_id);

                $vendor_id = $productData->getData('vu_product_vendor');

                $vendor = $this->_objectManager->create('Magenest\Vu\Model\Vendor')->load($vendor_id);
                $vendor->setTotalSales((int)($vendor->getTotalSales()) + 1);
                $vendor->save();
            }
        }
    }
}