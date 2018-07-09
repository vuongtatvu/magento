<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 09/02/2018
 * Time: 13:19
 */
namespace Magenest\ShopByBrand\Model\Brand;

use Magento\Framework\Option\ArrayInterface;
use Magenest\ShopByBrand\Model\Brand;

class Feature implements ArrayInterface
{
    public function toOptionArray()
    {
        $data =[
           [
               'value' => Brand::FEATURE_ENABLE,
               'label' => "Enable",
           ],
           [
               'value' => Brand::FEATURE_DISABLE,
               'label' => "Disable",
           ],
        ];
        return $data;
    }
}