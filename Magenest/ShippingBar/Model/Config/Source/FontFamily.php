<?php
namespace Magenest\ShippingBar\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class FontFamily implements ArrayInterface
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
            'Open Sans' => 'Open Sans',
            'Arial' => 'Arial',
            'Helvetica' => 'Helvetica',
            'Comic Sans MS' => 'Comic Sans MS',
            'Calibri' => 'Calibri',
            'Roboto' => 'Roboto',
            'Courier' => 'Courier',
            'Symbola' => 'Symbola',
            'Times New Roman' => 'Times New Roman',
        );
        return $choose;
    }
}
