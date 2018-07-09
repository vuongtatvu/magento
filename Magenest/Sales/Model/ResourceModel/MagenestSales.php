<?php
namespace	Magenest\Sales\Model\ResourceModel;
class	MagenestSales	extends
    \Magento\Framework\Model\ResourceModel\Db\AbstractDb	{
    public	function	_construct()	{
        $this->_init('magenest_sales',	'entity_id');
    }
}