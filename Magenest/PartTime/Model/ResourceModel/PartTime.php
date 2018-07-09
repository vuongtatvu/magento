<?php
namespace	Magenest\PartTime\Model\ResourceModel;
class	PartTime	extends
    \Magento\Framework\Model\ResourceModel\Db\AbstractDb	{
    public	function	_construct()	{
        $this->_init('magenest_part_time',	'member_id');
    }
}