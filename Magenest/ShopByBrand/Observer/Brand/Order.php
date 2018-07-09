<?php
/**
 * Created by PhpStorm.
 * User: duccanh
 * Date: 09/11/2016
 * Time: 09:37
 */
namespace Magenest\ShopByBrand\Observer\Brand;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class Order
 *
 * @package Magenest\ShopByBrand\Observer\Brand
 */
class Order extends \Magenest\ShopByBrand\Observer\Brand\AbstractObserver implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        /**
 * @var  $orderItem  \Magento\Sales\Model\Order\Item 
*/
        $orderItem = $observer->getEvent()->getItem();

        if (!$orderItem->getId()) {
            //order not saved in the database
            return $this;
        }

        /**
 * @var  $product \Magento\Catalog\Model\Product 
*/
        $product = $orderItem->getProduct();

        $productId = $product->getId();

        $productPrice = $product->getPrice();


        $collections = $this->_brandFactory->create()->updateOrderBrand($productId);

        if ($collections) {
            $data              = [];
            $dataProduct       = [];
            $dataFeatured      = [];

            foreach ($collections as $collection) {
                $brand = $this->_brandFactory->create()->load($collection['brand_id']);

                $collections = $this->_brandFactory->create()
                    ->getCollection()
                    ->addBrandIdToFilter($collection['brand_id'])
                    ->getData();

                foreach ($collections as $row) {
                    $dataProduct[$row['product_id']] = $row['position'];
                    $dataFeatured[] = $row['featured'];
                }

                $data['brand_products'] = json_encode($dataProduct);
                $data['featured_product'] = $dataFeatured;
                $data['order_total'] = $brand['order_total'] + $productPrice;

                $brand->addData($data)->save();
            }
        }
    }
}
