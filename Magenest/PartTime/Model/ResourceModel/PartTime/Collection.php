<?php

namespace Magenest\PartTime\Model\ResourceModel\PartTime;
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
        $this->_init('Magenest\PartTime\Model\PartTime',
            'Magenest\PartTime\Model\ResourceModel\PartTime');
    }
}