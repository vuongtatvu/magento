<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 9/12/16
 * Time: 23:06
 */

namespace Magenest\MapList\Model;

use Magento\Framework\Model\AbstractModel;
use Magenest\MapList\Helper\Constant;

class LocationCategory extends AbstractModel
{
    protected $_idFieldName = 'location_category_id';

    protected function _construct()
    {
        $this->_init(Constant::LOCATION_CATEGORY_RESOURCE_MODEL);
    }
}
