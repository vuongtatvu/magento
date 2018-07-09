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
namespace Magenest\ShopByBrand\Model\Config;

/**
 * Class Router
 *
 * @package Magenest\ShopByBrand\Model\Config
 */
class Router
{
    const ROUTER_BRAND = 'brand';
    const ROUTER_GROUP = 'group';
    const ROUTER_MEDIA = 'shopbybrand/brand/image';
    const ROUTER_STATIC = 'frontend/Magento/luma/en_US/Magento_Catalog/images/product/placeholder/thumbnail.jpg';
    protected $scope;
    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->scope=$scopeConfig;
    }
    public function getRouterBrand()
    {
        return $this->scope->getValue('shopbybrand/page/url', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}
