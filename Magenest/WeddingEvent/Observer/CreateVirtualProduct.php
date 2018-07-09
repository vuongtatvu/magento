<?php

namespace Magenest\WeddingEvent\Observer;

use    Magento\Framework\Event\ObserverInterface;

class   CreateVirtualProduct implements ObserverInterface
{


    public function execute(\Magento\Framework\Event\Observer $observer)
    {
//        getChange lay ten cua array truyen vao
        $wedding = $observer->getChange();

        $id = $wedding->getId();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();


        $_product = $objectManager->create('Magento\Catalog\Model\Product');

        $_product->setWeddingId($id);
        $_product->setName($id);
        $_product->setTypeId('virtual');
        $_product->setAttributeSetId(4);
        $_product->setSku($id);

        $_product->setWebsiteIds(array(1));
        $_product->setVisibility(4);
        $_product->setPrice(100000);
        $_product->setStatus(1);

        $_product->setStockData(array(
                'use_config_manage_stock' => 0, //'Use config settings' checkbox
                'manage_stock' => 1, //manage stock
                'min_sale_qty' => 1, //Minimum Qty Allowed in Shopping Cart
                'max_sale_qty' => 2, //Maximum Qty Allowed in Shopping Cart
                'is_in_stock' => 1, //Stock Availability
                'qty' => 100 //qty
            )
        );

        $_product->save();

    }
}