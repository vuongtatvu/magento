<?php
/**
 * Created by PhpStorm.
 * User: keysnt
 * Date: 31/05/2018
 * Time: 15:56
 */
namespace Magenest\Migrate\Model\ResourceModel;

class SalesOrderGrid extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb{
    public function _construct(){
        $this->_init('sales_order_grid', 'id');
    }
}