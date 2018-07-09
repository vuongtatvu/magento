<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_DemoModule
 * @author    Webkul
 * @copyright Copyright (c) 2010-2016 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Magenest\ShippingBar\Model\Config\Source;

/**
 * Used in creating options for getting product type value
 *
 */
class DayOfWeek
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'Monday', 'label' => __('Monday')],
            ['value' => 'Tuesday', 'label' => __('Tuesday')],
            ['value' => 'Wednesday', 'label' => __('Wednesday')],
            ['value' => 'Thursday', 'label' => __('Thursday')],
            ['value' => 'Friday', 'label' => __('Friday')],
            ['value' => 'Saturday', 'label' => __('Saturday')],
            ['value' => 'Sunday', 'label' => __('Sunday')],
        ];
    }
}