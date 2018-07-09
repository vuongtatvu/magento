<?php

namespace Magenest\Manufacturer\Model\ResourceModel\Manufacturer;
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
        $this->_init('Magenest\Manufacturer\Model\Manufacturer',
            'Magenest\Manufacturer\Model\ResourceModel\Manufacturer');
    }
}