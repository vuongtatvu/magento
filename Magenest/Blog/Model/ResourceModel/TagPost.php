<?php

namespace Magenest\Blog\Model\ResourceModel;

/**
 * Class TagPost
 * @package Magenest\Blog\Model\ResourceModel
 */
class TagPost extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('magenest_blog_tag_post', 'tag_id,post_id');
    }
}