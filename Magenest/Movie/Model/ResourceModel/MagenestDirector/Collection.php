<?php
namespace Magenest\Movie\Model\ResourceModel\MagenestDirector;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public function _construct()
    {
        $this->_init('Magenest\Movie\Model\MagenestDirector', 'Magenest\Movie\Model\ResourceModel\MagenestDirector');
    }
}