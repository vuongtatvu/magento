<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Block\Adminhtml\Post\Edit\Tab;

use Magento\Backend\Block\Widget\Grid as WidgetGrid;

/**
 * Class Comment
 * @package Magenest\Blog\Block\Adminhtml\Post\Edit\Tab
 */
class Comment extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magenest\Blog\Model\ResourceModel\Comment\Collection
     */
    protected $_cmtCollection;

    /**
     * Comment constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magenest\Blog\Model\ResourceModel\Comment\Collection $cmtCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magenest\Blog\Model\CommentFactory $cmtCollection,
        array $data = []
    ) {
        $this->_cmtCollection = $cmtCollection;
        parent::__construct($context, $backendHelper, $data);
        $this->setEmptyText(__('No Results Found'));
    }

    /**
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn('comment_id', [
            'header' => __('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'comment_id',
        ]);

        $this->addColumn('name', [
            'header' => __('Name'),
            'align' => 'left',
            'index' => 'name',
        ]);
        $this->addColumn('email', [
            'header' => __('Email'),
            'align' => 'left',
            'index' => 'email',
        ]);
        $this->addColumn('comment', [
            'header' => __('Comment'),
            'align' => 'left',
            'index' => 'comment',
        ]);
        $this->addColumn('created_at', [
            'header' => __('Created Time'),
            'index' => 'created_at',
        ]);
        $this->addColumn(
            'edit',
            [
                'header' => __('Delete'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Delete'),
                        'url' => [
                            'base' => 'blog/post/deleteComment',
                        ],
                        'field' => 'comment_id',
                    ],
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );
        return parent::_prepareColumns();
    }

    /**
     * Initialize the collection
     *
     * @return WidgetGrid
     */
    protected function _prepareCollection()
    {
        $collection = $this->_cmtCollection->create()->getCollection()->addFieldToFilter('post_id', $this->_request->getParam('id'));
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }
}
