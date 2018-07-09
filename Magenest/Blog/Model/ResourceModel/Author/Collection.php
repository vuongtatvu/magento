<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Model\ResourceModel\Author;

/**
 * Class Collection
 * @package Magenest\Blog\Model\ResourceModel\Author
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'author_id';

    /**
     * Initialize resource collection
     * *
    @return void
     */
    public function _construct()
    {
        $this->_init('Magenest\Blog\Model\Author', 'Magenest\Blog\Model\ResourceModel\Author');
    }
}
