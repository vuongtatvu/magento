<?php
namespace Magenest\StockStatus\Model\ResourceModel\ManagerIcon;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {
    /**
     * Initialize resource collection
     * @return void
     */
    public function _construct(){
        $this->_init('Magenest\StockStatus\Model\ManagerIcon','Magenest\StockStatus\Model\ResourceModel\ManagerIcon');
    }
}
