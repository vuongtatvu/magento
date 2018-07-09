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
 * Class SideBar
 *
 * @package Magenest\ShopByBrand\Model\Config
 */
class SideBar implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => '1',
                'label' => __('Sidebar Thumb'),
            ],
            [
                'value' => '2',
                'label' => __('Sidebar Filter'),
            ],
            [
                'value' => '3',
                'label' => __('Sidebar Carousel'),
            ],
            [
                'value' => '4',
                'label' => __('All'),
            ],
        ];
    }
}
