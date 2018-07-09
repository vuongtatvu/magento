<?php

namespace Magenest\ShippingBar\Block\Config;

class DayOfWeek extends \Magento\Config\Block\System\Config\Form\Field
{
    const CONFIG_PATH = 'shippingstore/schedule_week/day_of_week';

    protected $_template = 'Magenest_ShippingBar::config/checkbox.phtml';

    protected $_values = null;
    /**
     * @param \Magento\Backend\Block\Te->setHtmlId($element->getHtmlId());

        return $this->_toHtml();
    }

    public function getValues()
    {
        $values = [];
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        foreach ($objectManager->create('Magenest\ShippingBar\Model\Config\Source\DayOfWeek')->toOptionArray() as $value) {
            $values[$value['value']] = $value['label'];
        }

        return $values;
    }
    /**
     *
     * @param  $name
     * @return boolean
     */
    public function getIsChecked($name)
    {
        return in_array($name, $this->getCheckedValues());
    }
    /**
     *
     *get the checked value from config
     */
    public function getCheckedValues()
    {
        if (is_null($this->_values)) {
            $data = $this->getConfigData();
            if (isset($data[self::CONFIG_PATH])) {
                $data = $data[self::CONFIG_PATH];
            } else {
                $data = '';
            }
            $this->_values = explode(',', $data);
        }

        return $this->_values;
    }
}