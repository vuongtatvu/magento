<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Model\ResourceModel;

/**
 * Class Author
 * @package Magenest\Blog\Model\ResourceModel
 */
class Author extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('magenest_blog_author', 'author_id');
    }
}
