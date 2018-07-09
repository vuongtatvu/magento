<?php

namespace Magenest\Migrate\Model\ResourceModel\CustomerFlat;
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
        $this->_init('Magenest\Migrate\Model\CustomerFlat',
            'Magenest\Migrate\Model\ResourceModel\CustomerFlat');
    }
}