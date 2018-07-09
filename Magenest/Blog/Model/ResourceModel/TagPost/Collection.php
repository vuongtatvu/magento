<?php

namespace Magenest\Blog\Model\ResourceModel\TagPost;
/**
 * TagPost Collection
 */
class Collection extends
    \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'tag_id,post_id';
    /**
     * Initialize resource collection
     **
    @return void
     */
    public function _construct() {
        $this->_init('Magenest\Blog\Model\TagPost',
            'Magenest\Blog\Model\ResourceModel\TagPost');
    }
}