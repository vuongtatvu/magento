<?php
namespace Magenest\StockStatus\Model\ResourceModel;

use \Magento\Framework\Model\AbstractModel;

class ManagerIcon extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {
    protected function _construct(){
        $this->_init('magenest_icon','entity_id');
    }
}