<?php
/**
 * Created by PhpStorm.
 * User: keysnt
 * Date: 31/05/2018
 * Time: 15:53
 */
namespace Magenest\Migrate\Model;

class Product extends \Magento\Framework\Model\AbstractModel{
    public function _construct(){
        $this->_init('Magenest\Migrate\Model\ResourceModel\Product');
    }
}