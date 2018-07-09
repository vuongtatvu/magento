<?php
namespace	Magenest\Vu\Model\ResourceModel;
class	Vendor	extends
    \Magento\Framework\Model\ResourceModel\Db\AbstractDb	{
    public	function	_construct()	{
        $this->_init('magenest_test_vendor_vu',	'id');
    }
}