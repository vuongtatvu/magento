<?php
/**
 * Created by PhpStorm.
 * User: keysnt
 * Date: 31/05/2018
 * Time: 15:58
 */
namespace Magenest\Migrate\Model\ResourceModel\QuoteItem;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection{
    public function _construct(){
        $this->_init('Magenest\Migrate\Model\QuoteItem','Magenest\Migrate\Model\ResourceModel\QuoteItem');
    }
}