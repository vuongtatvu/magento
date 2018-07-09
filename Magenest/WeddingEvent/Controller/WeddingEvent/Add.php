<?php

namespace Magenest\WeddingEvent\Controller\WeddingEvent;


class Add extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        if ($_POST) {

            $wedding_id = $this->getRequest()->getParam('wedding_id');

            $price = $this->getRequest()->getParam('amount');

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

            $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
            
            //Lay tat ca cacs thuoc tinh co gia tri cua san pham
            $collection = $productCollection->addAttributeToSelect('*')->load();

            $cart = $this->_objectManager->create('Magento\Checkout\Model\Cart');


          
            foreach ($collection as $item) {
                if ($item['wedding_id'] == $wedding_id) {
                    break;
                }
            }

            $id = $item['entity_id'];
            $product = $objectManager->create('\Magento\Catalog\Model\Product')->load($id);
            $product->setPrice($price);

            $cart->addProduct($product,1);
            $cart->save();

            $this->_redirect('checkout/cart/');
        }
    }
}