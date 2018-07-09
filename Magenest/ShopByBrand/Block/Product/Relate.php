<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_ShopByBrand extension
 * NOTICE OF LICENSE
 *
 * @category Magenest
 * @package  Magenest_ShopByBrand
 * @author   Chienbigstar <chienbigstar@gmail.com>
 */
namespace Magenest\ShopByBrand\Block\Product;

/**
 * Class Relate
 *
 * @package Magenest\ShopByBrand\Block\Product
 */
class Relate extends \Magento\Catalog\Block\Product\AbstractProduct
{
    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $product;

    /**
     * @var \Magenest\ShopByBrand\Helper\Brand
     */
    protected $brand;

    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Catalog\Model\Product         $product
     * @param \Magenest\ShopByBrand\Helper\Brand     $brand
     * @param array                                  $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\Product $product,
        \Magenest\ShopByBrand\Helper\Brand $brand,
        array $data = []
    ) {

        parent::__construct($context, $data);
        $this->brand   = $brand;
        $this->product = $this->getProduct();
    }

    /**
     * @return $this
     */
    public function getItemCollection()
    {
        $brandhelper = $this->brand->setProduct($this->product);
        $collection  = $this->product->getCollection()
            ->addIdFilter($brandhelper->getIdRelateProduct(), false)
            ->addIdFilter($this->product->getId(), true);
        $this->_addProductAttributesAndPrices($collection);
        return $collection;
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection|\Magento\Framework\Data\Collection
     */
    protected function _addProductAttributesAndPrices(
        \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
    ) {
        $number = $this->_scopeConfig->getValue('shopbybrand/product/relate');
        return $collection
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect($this->_catalogConfig->getProductAttributes())
            ->addUrlRewrite()
            ->setPageSize($number);
    }

    /**
     * @return bool
     */
    public function isShow()
    {
        $brandhelper = $this->brand->setProduct($this->getProduct());
        if (!$brandhelper->isShowRelate()) {
            return false;
        }

        return true;
    }
}
