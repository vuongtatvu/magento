<?php
namespace Magenest\ShippingBar\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Products implements ArrayInterface
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
            '1' => 'Total Price Of Products Purchased',
            '2' => 'Total Number Of Products Purchased',
        );
        return $choose;
    }
}
