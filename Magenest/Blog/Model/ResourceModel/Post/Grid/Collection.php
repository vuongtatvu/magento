<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Model\ResourceModel\Post\Grid;

use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Search\AggregationInterface;
use Magenest\Blog\Model\ResourceModel\Post\Collection as PostCollection;

/**
 * Class Collection
 * @package Magenest\Blog\Model\ResourceModel\Post\Grid
 */
class Collection extends PostCollection implements SearchResultInterface
{

    protected $aggregations;

    const CAT_PROD_LINK_ALIAS = 'category_ids_table';
    const CAT_PROD_LINK = 'magenest_blog_category_post';

    /**
     * {@inheritdoc}
     */
    public function getAggregations()
    {

        return $this->aggregations;
    }

    /**
     * {@inheritdoc}
     */
    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllIds($limit = null, $offset = null)
    {

        return $this->getConnection()->fetchCol($this->_getAllIdsSelect($limit, $offset), $this->_bindParams);
    }

    /**
     * {@inheritdoc}
     */
    public function getSearchCriteria()
    {

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function setSearchCriteria(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null)
    {

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalCount()
    {

        return $this->getSize();
    }

    /**
     * {@inheritdoc}
     */
    public function setTotalCount($totalCount)
    {

        return $this;
    }

    /**
     * @param array|null $items
     * @return $this
     */
    public function setItems(array $items = null)
    {

        return $this;
    }

    /**
     * @param array|int|\Magento\Eav\Model\Entity\Attribute\AttributeInterface|string $attribute
     * @param null $condition
     * @param string $joinType
     * @return $this
     */
    public function addAttributeToFilter($attribute, $condition = null, $joinType = 'inner')
    {
        $select = $this->getSelect();
        if ($attribute !== "category_ids" ) {
            return parent::addAttributeToFilter($attribute, $condition, $joinType);
        }
        if (isset($select->getPart($select::FROM)[self::CAT_PROD_LINK_ALIAS])) {
            return $this;
        }
        $this->joinCategoryIdsTable($select);
        $this->addConditionToSelect($select, $condition);

        return $this;
    }

    /**
     * Joins the category / post linking table into this queyr.
     * 
     * @param \Magento\Framework\DB\Select $select
     * @return void
     */
    private function joinCategoryIdsTable(\Magento\Framework\DB\Select $select)
    {
        $select->group('entity_id');
        $select->joinInner(
            [self::CAT_PROD_LINK_ALIAS => $this->getTable(self::CAT_PROD_LINK)],
            'e.entity_id = ' . self::CAT_PROD_LINK_ALIAS . '.post_id',
            'category_id'
        );
    }

    /**
     * Adds the condition relating to category ids.
     * 
     * @param \Magento\Framework\DB\Select $select
     * @param null $condition
     * @return void
     */
    private function addConditionToSelect(\Magento\Framework\DB\Select $select, $condition = null)
    {
        $select->where($this->_getConditionSql(self::CAT_PROD_LINK_ALIAS . '.category_id', $condition));
    }
}
