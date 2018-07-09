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
 * Class Product
 *
 * @package Magenest\ShopByBrand\Model\Config
 */
class Product implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
                [
                 'value' => '0',
                 'label' => __('No'),
                ],
                [
                 'value' => '1',
                 'label' => __('Show brand name'),
                ],
                [
                 'value' => '2',
                 'label' => __('Show brand icon'),
                ],
                [
                 'value' => '3',
                 'label' => __('Show brand name and icon'),
                ],
               ];
    }
}
