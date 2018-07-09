<?php
namespace Magenest\Movie\Plugin\Product;
class NameProductChild
{
    public function aroundGetItemData( $subject,$proceed, $item)
    {
        $result = $proceed($item);

        //tao object de de su dung
        /* @var  \Magento\Framework\App\ObjectManager */
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
        $collection = $productCollection->addAttributeToSelect('*')->load();

        /** @var \Magento\Store\Model\StoreManagerInterface $storeManager */
        $storeManager = $objectManager->get(\Magento\Store\Model\StoreManagerInterface::class);
        $mediaUrl = $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        foreach ($collection as $product) {
            if ($result['product_sku'] == $product->getSku()) {
                $result['product_name'] = $product->getName();
                $result['product_image']['src'] = $mediaUrl . 'catalog/product' . $product->getImage();
            }
        }
        
        return $result;
    }
}