<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Model\ResourceModel;

use Magento\Framework\DataObject;
use Magento\Eav\Model\Entity\AbstractEntity;
use Magento\Framework\App\ObjectManager;
use Magento\Eav\Model\Entity\Context;
use Magenest\Blog\Model\Config;
use Magento\Framework\Filter\FilterManager;

/**
 * Class Category
 * @package Magenest\Blog\Model\ResourceModel
 */
class Category extends AbstractEntity
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var FilterManager
     */
    protected $filter;

    /**
     * @param Config        $config
     * @param FilterManager $filter
     * @param Context       $context
     * @param array         $data
     */
    public function __construct(
        Config $config,
        FilterManager $filter,
        Context $context,
        $data = []
    ) {
        $this->config = $config;
        $this->filter = $filter;
        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Eav\Model\Entity\Type
     */
    public function getEntityType()
    {
        if (empty($this->_type)) {
            $this->setType(\Magenest\Blog\Model\Category::ENTITY);
        }

        return parent::getEntityType();
    }

    /**
     * @param DataObject $category
     * @return $this
     */
    protected function _beforeSave(DataObject $category)
    {
        /** @var \Magenest\Blog\Model\Category $category */

        parent::_beforeSave($category);
        if (!$category->getChildrenCount()) {
            $category->setChildrenCount(0);
        }

        if (!$category->getData('url_key')) {
            $category->setData('url_key', $this->filter->translitUrl($category->getName()));
        }

        if ($category->isObjectNew()) {
            if (!$category->hasParentId()) {
                $category->setParentId(1);
            }

            /** @var \Magenest\Blog\Model\Category $parent */
            $parent = ObjectManager::getInstance()
                ->create('Magenest\Blog\Model\Category')
                ->load($category->getParentId());
            $category->setPath($parent->getPath());
            if ($category->getPosition() === null) {
                $category->setPosition($this->getMaxPosition($category->getPath()) + 1);
            }

            $path = explode('/', $category->getPath());
            $level = count($path) - ($category->getId() ? 1 : 0);
            $toUpdateChild = array_diff($path, [$category->getId()]);
            if (!$category->hasPosition()) {
                $category->setPosition($this->getMaxPosition(implode('/', $toUpdateChild)) + 1);
            }

            if (!$category->hasLevel()) {
                $category->setLevel($level);
            }

            if (!$category->getId() && $category->getPath()) {
                $category->setPath($category->getPath() . '/');
            }

            $this->getConnection()->update(
                $this->getEntityTable(),
                ['children_count' => new \Zend_Db_Expr('children_count+1')],
                ['entity_id IN(?)' => $toUpdateChild]
            );
        }

        return $this;
    }

    /**
     * @param DataObject $object
     * @return $this
     */
    protected function _afterSave(DataObject $object)
    {
        /** @var \Magenest\Blog\Model\Category $object */

        if (substr($object->getPath(), -1) == '/' || !$object->getPath()) {
            $object->setPath($object->getPath() . $object->getId());
            $this->savePath($object);
        }

        if ($object->dataHasChangedFor('parent_id')) {
            $newParent = \Magento\Framework\App\ObjectManager::getInstance()
                ->create('Magenest\Blog\Model\Category')
                ->load($object->getParentId());
            $this->changeParent($object, $newParent);
        }

        return parent::_afterSave($object);
    }

    /**
     * @param $object
     * @return $this
     */
    protected function savePath($object)
    {
        if ($object->getId()) {
            $this->getConnection()->update(
                $this->getEntityTable(),
                ['path' => $object->getPath()],
                ['entity_id = ?' => $object->getId()]
            );
            $object->unsetData('path_ids');
        }

        return $this;
    }

    /**
     * @param \Magenest\Blog\Model\Category $category
     * @param \Magenest\Blog\Model\Category $newParent
     * @param null $afterCategoryId
     * @return $this
     */
    public function changeParent(
        \Magenest\Blog\Model\Category $category,
        \Magenest\Blog\Model\Category $newParent,
        $afterCategoryId = null
    ) {
        $childrenCount = $this->getChildrenCount($category->getId()) + 1;
        $table = $this->getEntityTable();
        $connection = $this->getConnection();
        $levelFiled = $connection->quoteIdentifier('level');
        $pathField = $connection->quoteIdentifier('path');

        /**
         * Decrease children count for all old category parent categories
         */
        $connection->update(
            $table,
            ['children_count' => new \Zend_Db_Expr('children_count - ' . $childrenCount)],
            ['entity_id IN(?)' => $category->getParentIds()]
        );

        /**
         * Increase children count for new category parents
         */
        $connection->update(
            $table,
            ['children_count' => new \Zend_Db_Expr('children_count + ' . $childrenCount)],
            ['entity_id IN(?)' => $newParent->getPathIds()]
        );

        $position = $this->processPositions($category, $newParent, $afterCategoryId);

        $newPath = sprintf('%s/%s', $newParent->getPath(), $category->getId());
        $newLevel = $newParent->getLevel() + 1;
        $levelDisposition = $newLevel - $category->getLevel();

        /**
         * Update children nodes path
         */
        $connection->update(
            $table,
            [
                'path'  => new \Zend_Db_Expr(
                    'REPLACE(' . $pathField . ',' . $connection->quote(
                        $category->getPath() . '/'
                    ) . ', ' . $connection->quote(
                        $newPath . '/'
                    ) . ')'
                ),
                'level' => new \Zend_Db_Expr($levelFiled . ' + ' . $levelDisposition)
            ],
            [$pathField . ' LIKE ?' => $category->getPath() . '/%']
        );

        /**
         * Update moved category data
         */
        $data = [
            'path'      => $newPath,
            'level'     => $newLevel,
            'position'  => $position,
            'parent_id' => $newParent->getId(),
        ];
        $connection->update($table, $data, ['entity_id = ?' => $category->getId()]);

        // Update category object to new data
        $category->addData($data);
        $category->unsetData('path_ids');

        return $this;
    }

    /**
     * @param $categoryId
     * @return string
     */
    public function getChildrenCount($categoryId)
    {
        $select = $this->getConnection()->select()->from(
            $this->getEntityTable(),
            'children_count'
        )->where(
            'entity_id = :entity_id'
        );
        $bind = ['entity_id' => $categoryId];

        return $this->getConnection()->fetchOne($select, $bind);
    }

    /**
     * @param $category
     * @param $newParent
     * @param $afterCategoryId
     * @return int|string
     */
    protected function processPositions($category, $newParent, $afterCategoryId)
    {
        $table = $this->getEntityTable();
        $connection = $this->getConnection();
        $positionField = $connection->quoteIdentifier('position');

        $bind = ['position' => new \Zend_Db_Expr($positionField . ' - 1')];
        $where = [
            'parent_id = ?'         => $category->getParentId(),
            $positionField . ' > ?' => $category->getPosition(),
        ];
        $connection->update($table, $bind, $where);

        /**
         * Prepare position value
         */
        if ($afterCategoryId) {
            $select = $connection->select()->from($table, 'position')->where('entity_id = :entity_id');
            $position = $connection->fetchOne($select, ['entity_id' => $afterCategoryId]);
            $position += 1;
        } else {
            $position = 1;
        }

        $bind = ['position' => new \Zend_Db_Expr($positionField . ' + 1')];
        $where = ['parent_id = ?' => $newParent->getId(), $positionField . ' >= ?' => $position];
        $connection->update($table, $bind, $where);

        return $position;
    }

    /**
     * @param $path
     * @return int|string
     */
    protected function getMaxPosition($path)
    {
        $connection = $this->getConnection();
        $positionField = $connection->quoteIdentifier('position');
        $level = count(explode('/', $path));
        $bind = ['c_level' => $level, 'c_path' => $path . '/%'];
        $select = $connection->select()->from(
            $this->getTable('magenest_blog_category_entity'),
            'MAX(' . $positionField . ')'
        )->where(
            $connection->quoteIdentifier('path') . ' LIKE :c_path'
        )->where($connection->quoteIdentifier('level') . ' = :c_level');

        $position = $connection->fetchOne($select, $bind);
        if (!$position) {
            $position = 0;
        }

        return $position;
    }
}
