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

use Magento\Framework\View\Element\Template\Context;
use Magenest\ShopByBrand\Helper\Brand as BrandHelper;
use Magento\Framework\Registry;

/**
 * Class Brand
 *
 * @package Magenest\ShopByBrand\Block\Product
 */
class Brand extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_template = "product/product_brand.phtml";

    /**
     * @var \Magenest\ShopByBrand\Helper\Brand
     */
    protected $_brandHelper;
    /**
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * @var
     */
    protected $data;

    /**
     * @param Context     $context
     * @param BrandHelper $brandHelper
     * @param array       $data
     */
    public function __construct(
        Context $context,
        BrandHelper $brandHelper,
        Registry $coreRegistry,
        array $data = []
    ) {
        $this->_coreRegistry=$coreRegistry;
        $this->_brandHelper = $brandHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public function getScopeConfig()
    {
        return $this->_scopeConfig;
    }

    /**
     * @return mixed
     */
    public function getModeDisplay()
    {
        return $this->_scopeConfig->getValue('shopbybrand/product/show');
    }

    /**
     * @return bool
     */
    public function isShowBrand()
    {
        $brandhelper = $this->_brandHelper->setProduct($this->getProduct());
        return $brandhelper->isShowBrand();
    }

    /**
     * @return array
     */
    public function getBrand()
    {
        return $this->_brandHelper->getBrand();
    }

    /**
     * Get Product
     */
    public function getProduct()
    {
        return $this->_coreRegistry->registry('product');
    }
}
