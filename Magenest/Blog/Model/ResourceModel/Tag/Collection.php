<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Model\ResourceModel\Tag;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Magenest\Blog\Model\ResourceModel\Tag
 */
class Collection extends AbstractCollection
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('Magenest\Blog\Model\Tag', 'Magenest\Blog\Model\ResourceModel\Tag');
    }

    /**
     * @return $this
     */
    public function joinPopularity()
    {
        $this->getSelect()
            ->joinLeft(
                ['tag_post' => $this->getTable('magenest_blog_tag_post')],
                'main_table.tag_id = tag_post.tag_id',
                ['popularity' => 'count(tag_post.tag_id)']
            )->group('main_table.tag_id');

        return $this;
    }

    /**
     * @return $this
     */
    public function joinNotEmptyFields()
    {
        $select = $this->getSelect();
        $select->joinRight(
            ['article_tag' => $this->getTable('magenest_kb_article_tag')],
            'main_table.tag_id = at_tag_id',
            ['ratio' => 'count(main_table.tag_id)']
        )
            ->group('tag_id');

        return $this;
    }
}
