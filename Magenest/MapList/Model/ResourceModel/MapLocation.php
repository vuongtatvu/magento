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

class MapLocation extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(Constant::MAP_LOCATION_TABLE, Constant::MAP_LOCATION_TABLE_ID);
    }
}
