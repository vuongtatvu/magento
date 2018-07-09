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
namespace Magenest\ShopByBrand\Block\Brand\View;

use Magento\Catalog\Api\CategoryRepositoryInterface;

/**
 * Class Listing
 *
 * @package Magenest\ShopByBrand\Block\Brand\View
 */
class Listing extends \Magento\Catalog\Block\Product\ListProduct
{
    /**
     * @var string
     */
    protected $_template = "Magento_Catalog::product/list.phtml";

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_product;

    /**
     * @var \Magenest\ShopByBrand\Model\Brand
     */
    protected $_brand;

    /**
     * @param \Magento\Catalog\Block\Product\Context    $context
     * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param \Magento\Catalog\Model\Layer\Resolver     $layerResolver
     * @param CategoryRepositoryInterface               $categoryRepository
     * @param \Magento\Framework\Url\Helper\Data        $urlHelper
     * @param \Magento\Catalog\Model\ProductFactory     $product
     * @param \Magenest\ShopByBrand\Model\Brand         $brand
     * @param array                                     $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Magento\Catalog\Model\ProductFactory $product,
        \Magenest\ShopByBrand\Model\Brand $brand,
        array $data = []
    ) {
        $this->_product = $product;
        $this->_brand   = $brand;
        parent::__construct($context, $postDataHelper, $layerResolver, $categoryRepository, $urlHelper, $data);
    }

    /**
     * @return array
     */
    public function getListId()
    {
        $id         = $this->getRequest()->getParam('brand_id');
        $collection = $this->_brand->getCollection()
            ->addBrandIdToFilter($id)
            ->getData();
        $data       = array();
        foreach ($collection as $row) {
            $data[] = $row['product_id'];
        }


        return $data;
    }

    /**
     * @return \Magento\Eav\Model\Entity\Collection\AbstractCollection
     */
    protected function _getProductCollection()
    {
        $Ids = $this->getListId();
        if (is_null($this->_productCollection)) {
            $collection = $this->_product->create()->getCollection()
                ->addIdFilter($Ids);

            $this->_catalogLayer->prepareProductCollection($collection);
            $this->_productCollection = $collection;
        }

        return parent::_getProductCollection();
    }
}
