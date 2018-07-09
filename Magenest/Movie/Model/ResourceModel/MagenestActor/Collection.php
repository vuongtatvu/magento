<?php
namespace Magenest\Movie\Model\ResourceModel\MagenestActor;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public function _construct()
    {
        $this->_init('Magenest\Movie\Model\MagenestActor', 'Magenest\Movie\Model\ResourceModel\MagenestActor');
    }
}