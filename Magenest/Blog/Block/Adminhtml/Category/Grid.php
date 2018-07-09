<?php

namespace Magenest\Blog\Block\Adminhtml\Category;

use Magento\Backend\Block\Widget\Grid\Extended as ExtendedGrid;
use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Helper\Data as BackendHelper;
use Magenest\Blog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;

/**
 * Class Grid
 * @package Magenest\Blog\Block\Adminhtml\Category
 */
class Grid extends ExtendedGrid
{
    /**
     * @var CategoryCollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @param CategoryCollectionFactory $postCollectionFactory
     * @param Context                   $context
     * @param BackendHelper             $backendHelper
     * @param array                     $data
     */
    public function __construct(
        CategoryCollectionFactory $postCollectionFactory,
        Context $context,
        BackendHelper $backendHelper,
        array $data = []
    ) {
        $this->categoryCollectionFactory = $postCollectionFactory;

        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('blog_category_grid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->categoryCollectionFactory->create();
        $collection = $collection->addAttributeToSelect('*');
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return $this
     * @throws \Exception
     */
    protected function _prepareColumns()
    {
        $this->addColumn('name', [
            'header'   => __('Title'),
            'index'    => 'name',
            'filter'   => false,
            'sortable' => false,
            'renderer' => 'Magenest\Blog\Block\Adminhtml\Category\Grid\Renderer\Title'
        ]);

        $this->addColumn('status', [
            'header'   => __('Status'),
            'index'    => 'status',
            'type'     => 'options',
            'sortable' => false,
            'options'  => [
                0 => __('Disabled'),
                1 => __('Enabled'),
            ],
        ]);

        return parent::_prepareColumns();
    }

    /**
     * @param \Magenest\Blog\Model\Category $row
     * @return string
     */
    public function getRowUrl($row)
    {

        return $this->getUrl('*/*/edit', ['id' => $row->getId()]);
    }
}
