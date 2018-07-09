<?php
namespace Magenest\StockStatus\Model\ResourceModel;

use \Magento\Framework\Model\AbstractModel;

class QuantityRule extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {
    protected function _construct(){
        $this->_init('magenest_stockstatus_managerqtyrule','entity_id');
    }
}