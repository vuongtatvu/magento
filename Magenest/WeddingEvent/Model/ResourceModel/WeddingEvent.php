<?php
namespace	Magenest\WeddingEvent\Model\ResourceModel;
class	WeddingEvent	extends
    \Magento\Framework\Model\ResourceModel\Db\AbstractDb	{
    public	function	_construct()	{
        $this->_init('magenest_wedding_event',	'id');
    }
}