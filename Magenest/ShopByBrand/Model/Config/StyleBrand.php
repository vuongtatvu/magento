<?php
/**
 * Created by PhpStorm.
 * User: duccanh
 * Date: 17/06/2016
 * Time: 10:27
 */
namespace Magenest\ShopByBrand\Model\Config;

/**
 * Class StyleBrand
 *
 * @package Magenest\ShopByBrand\Model\Config
 */
class StyleBrand implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => '1',
                'label' => __('Slider'),
            ],
            [
                'value' => '2',
                'label' => __('Alphabetical Row'),
            ],
            [
                'value' => '3',
                'label' => __('Grid'),
            ],
            [
                'value' => '4',
                'label' => __('Alphabetical Column'),
            ],
        ];
    }
}
