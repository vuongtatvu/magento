<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 9/12/16
 * Time: 23:07
 */

namespace Magenest\MapList\Model\ResourceModel\Map;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magenest\MapList\Helper\Constant;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'map_id';

    public function _construct()
    {
        $this->_init(Constant::MAP_MODEL, Constant::MAP_RESOURCE_MODEL);
    }
}
