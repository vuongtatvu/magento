<?php
namespace Magenest\ShippingBar\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Pages implements ArrayInterface
{

    /*
     * Option getter
     * @return array
     */
    public function toOptionArray()
    {
        $arr = $this->toArray();
        $ret = array();
        foreach ($arr as $key => $value) {
            $ret[] = array(
                'value' => $key,
                'label' => $value
            );
        }

        return $ret;
    }

    /*
     * Get options in "key-value" format
     * @return array
     */
    public function toArray()
    {
        $choose = array(
            '1' => 'All Pages',
            '2' => 'Home Page',
            '3' => 'Catalog Pages',
            '4' => 'Product Pages',
            '5' => 'Checkout Pages',
        );
        return $choose;
    }
}
