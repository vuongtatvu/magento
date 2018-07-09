<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 10/14/16
 * Time: 11:01
 */

namespace Magenest\MapList\Model\Config\Source;

class TravelMode implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'DRIVING', 'label' => __('Driving')],
            ['value' => 'WALKING', 'label' => __('Walking')],
            ['value' => 'BICYCLING', 'label' => __('Bicycling')],
            ['value' => 'TRANSIT', 'label' => __('Transit')]
        ];
    }
}
