<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 09/02/2018
 * Time: 13:19
 */

namespace Magenest\ShopByBrand\Model\Group;

use Magenest\ShopByBrand\Model\Group;
use Magento\Framework\Option\ArrayInterface;
use Magenest\ShopByBrand\Model\Brand;

class Status implements ArrayInterface
{
    public function toOptionArray()
    {
        $data =[
            [
                'value' => Group::STATUS_ENABLE,
                'label' => "Enable",
            ],
            [
                'value' => Group::STATUS_DISABLE,
                'label' => "Disable",
            ],
        ];
        return $data;
    }
}