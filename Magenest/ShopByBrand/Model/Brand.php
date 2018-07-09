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
namespace Magenest\ShopByBrand\Model;

use Magento\CatalogRule\Model\Rule\Condition\CombineFactory;
use Magento\CatalogRule\Model\Rule\Action\CollectionFactory;

/**
 * Class Brand
 *
 * @package Magenest\ShopByBrand\Model
 */
class Brand extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = '_brand';
    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 2;
    const FEATURE_ENABLE = 1;
    const FEATURE_DISABLE = 2;


    /**
     * Construct
     */
    protected function _construct()
    {
        $this->_init('Magenest\ShopByBrand\Model\ResourceModel\Brand');
        $this->setIdFieldName('brand_id');
    }

    /**
     * Retrieve array of product id's for category
     *
     * The array returned has the following format:
     * array($productId => $position)
     *
     * @return array
     */
    public function getProductsPosition()
    {
        if (!$this->getId()) {
            return [];
        }

        $array = $this->getData('products_position');
        if ($array === null) {
            $array = $this->getResource()->getProductsPosition($this);
            $this->setData('products_position', $array);
        }

        return $array;
    }

    /**
     * Update Order Brand
     *
     * @return mixed
     */
    public function updateOrderBrand($productId)
    {
        return $this->getResource()->updateOrderBrand($productId);
    }
}
