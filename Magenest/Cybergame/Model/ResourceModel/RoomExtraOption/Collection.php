<?php

namespace Magenest\Cybergame\Model\ResourceModel\RoomExtraOption;
/**
 *    Subscription    Collection
 */
class    Collection extends
    \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     *    Initialize    resource    collection
     *
     * @return    void
     */
    public function _construct()
    {
        $this->_init('Magenest\Cybergame\Model\RoomExtraOption',
            'Magenest\Cybergame\Model\ResourceModel\RoomExtraOption');
    }
}