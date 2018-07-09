<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 9/12/16
 * Time: 23:09
 */

namespace Magenest\MapList\Model\ResourceModel;

use Magenest\MapList\Helper\Constant;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Category extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(Constant::CATEGORY_TABLE, Constant::CATEGORY_TABLE_ID);
    }
}
