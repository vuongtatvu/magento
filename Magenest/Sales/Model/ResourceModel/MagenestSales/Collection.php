<?php

namespace Magenest\Sales\Model\ResourceModel\MagenestSales;
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
        $this->_init('Magenest\Sales\Model\MagenestSales',
            'Magenest\Sales\Model\ResourceModel\MagenestSales');
    }
}