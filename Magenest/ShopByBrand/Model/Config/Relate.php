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
 * Class Relate
 *
 * @package Magenest\ShopByBrand\Model\Config
 */
class Relate implements \Magento\Framework\Option\ArrayInterface
{


    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
                [
                 'value' => '1',
                 'label' => __('1'),
                ],
                [
                 'value' => '2',
                 'label' => __('2'),
                ],
                [
                 'value' => '3',
                 'label' => __('3'),
                ],
                [
                 'value' => '4',
                 'label' => __('4'),
                ],
                [
                 'value' => '5',
                 'label' => __('5'),
                ],
                [
                 'value' => '6',
                 'label' => __('6'),
                ],

               ];
    }
}
