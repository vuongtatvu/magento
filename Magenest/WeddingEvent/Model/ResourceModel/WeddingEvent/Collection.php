<?php

namespace Magenest\WeddingEvent\Model\ResourceModel\WeddingEvent;
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
        $this->_init('Magenest\WeddingEvent\Model\WeddingEvent',
            'Magenest\WeddingEvent\Model\ResourceModel\WeddingEvent');
    }
}