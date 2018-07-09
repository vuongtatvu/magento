<?php
namespace Magenest\Movie\Controller\Index;
class Collection extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        $productCollection = $this->_objectManager
            ->create('Magento\Catalog\Model\ResourceModel\Product\Collection')
            ->addAttributeToSelect([
                'name',
                'price',
                'image',]);

        $output = '';

        //$productCollection->setDataToAll('price',	20);


        foreach ($productCollection as $product) {
            $output .= \Zend_Debug::dump($product->debug(), null, false);
        }

        //$output	=	$productCollection->getSelect()->__toString();
        $this->getResponse()->setBody($output);

        //$productCollection->save();

    }
}