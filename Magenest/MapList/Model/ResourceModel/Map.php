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

class Map extends AbstractDb
{
    protected $_date;

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);
        $this->_date = $date;
    }

    protected function _construct()
    {
        $this->_init(Constant::MAP_TABLE, Constant::MAP_TABLE_ID);
    }
}
