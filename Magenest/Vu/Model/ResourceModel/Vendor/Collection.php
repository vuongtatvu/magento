<?php

namespace Magenest\Vu\Model\ResourceModel\Vendor;
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
        $this->_init('Magenest\Vu\Model\Vendor',
            'Magenest\Vu\Model\ResourceModel\Vendor');
    }
}