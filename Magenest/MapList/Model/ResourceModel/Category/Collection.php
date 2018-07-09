<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 9/12/16
 * Time: 23:07
 */

namespace Magenest\MapList\Model\ResourceModel\Category;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magenest\MapList\Helper\Constant;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'category_id';

    public function _construct()
    {
        $this->_init(Constant::CATEGORY_MODEL, Constant::CATEGORY_RESOURCE_MODEL);
    }
}
