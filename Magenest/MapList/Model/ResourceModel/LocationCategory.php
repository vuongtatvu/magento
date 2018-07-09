<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 9/12/16
 * Time: 23:06
 */

namespace Magenest\MapList\Model\ResourceModel;

use Magenest\MapList\Helper\Constant;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class LocationCategory extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(Constant::LOCATION_CATEGORY_TABLE, Constant::LOCATION_CATEGORY_TABLE_ID);
    }
}
