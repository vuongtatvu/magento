<?php
namespace Magenest\Movie\Model\ResourceModel\MagenestMovieActor;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public function _construct()
    {
        $this->_init('Magenest\Movie\Model\MagenestMovieActor', 'Magenest\Movie\Model\ResourceModel\MagenestMovieActor');
    }
}