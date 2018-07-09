<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Model\ResourceModel\Category;

use Magento\Eav\Model\Entity\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Magenest\Blog\Model\ResourceModel\Category
 */
class Collection extends AbstractCollection
{
    /**
     * @var bool
     */
    protected $fromRoot = true;

    protected function _construct()
    {
        $this->_init('Magenest\Blog\Model\Category', 'Magenest\Blog\Model\ResourceModel\Category');
    }

    /**
     * @return $this
     */
    public function addNameToSelect()
    {

        return $this->addAttributeToSelect(['name']);
    }

    /**
     * @return $this
     */
    public function addVisibilityFilter()
    {
        $this->addAttributeToFilter('status', 1);

        return $this;
    }

    /**
     * @return $this
     */
    public function excludeRoot()
    {
        $this->fromRoot = false;

        return $this->addFieldToFilter('entity_id', ['neq' => 1]);
    }

    /**
     * @param int|null $parentId
     * @return \Magenest\Blog\Model\Category[]
     */
    public function getTree($parentId = null)
    {
        $list = [];
        if ($parentId == null) {
            $parentId = $this->fromRoot ? 0 : 1;
        }
        $collection = clone $this;
        $collection->addFieldToFilter('parent_id', $parentId)
            ->setOrder('position', 'asc');
        foreach ($collection as $item) {
            $list[$item->getId()] = $item;
            if ($item->getChildrenCount()) {
                $items = $this->getTree($item->getId());
                foreach ($items as $child) {
                    $list[$child->getId()] = $child;
                }
            }
        }

        return $list;
    }

    /**
     * {@inheritdoc}
     */
    protected function _toOptionArray($valueField = 'id', $labelField = 'name', $additional = [])
    {
        $result = [];
        $this->addAttributeToSelect('name');
        foreach ($this->getTree(0) as $item) {
            $result[] = [
                'value' => $item->getId(),
                'label' => str_repeat(' ', $item->getLevel() * 5) . $item->getName()
            ];
        }

        return $result;
    }
}
