<?php
namespace Magenest\ShippingBar\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class DisplayType implements ArrayInterface
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
            'bounceInDown' => 'Down',
            'bounceInLeft' => 'Left',
            'bounceInRight' => 'Right',
        );
        return $choose;
    }
}
