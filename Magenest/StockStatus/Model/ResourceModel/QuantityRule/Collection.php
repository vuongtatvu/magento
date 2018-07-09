<?php
namespace Magenest\StockStatus\Model\ResourceModel\QuantityRule;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {
    /**
     * Initialize resource collection
     * @return void
     */
    public function _construct(){
        $this->_init('Magenest\StockStatus\Model\QuantityRule','Magenest\StockStatus\Model\ResourceModel\QuantityRule');
    }
}