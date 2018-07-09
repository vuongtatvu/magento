<?php
namespace	Magenest\Migrate\Model\ResourceModel;
class	CustomerFlat	extends
    \Magento\Framework\Model\ResourceModel\Db\AbstractDb	{
    public	function	_construct()	{
        $this->_init('customer_grid_flat',	'entity_id');
    }
}