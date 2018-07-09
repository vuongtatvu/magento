<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Block\Adminhtml\Post\Edit;

/**
 * Class Tabs
 * @package Magenest\Blog\Block\Adminhtml\Post\Edit
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('blog__post-tabs');
        $this->setDestElementId('blog_details');
    }

    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->addTab('general_section', [
            'label'   => __('General Information'),
            'content' => $this->getLayout()
                ->createBlock('Magenest\Blog\Block\Adminhtml\Post\Edit\Tab\General')->toHtml(),
        ]);

        $this->addTab('meta_section', [
            'label'   => __('Search Engine Optimization'),
            'content' => $this->getLayout()
                ->createBlock('Magenest\Blog\Block\Adminhtml\Post\Edit\Tab\Meta')->toHtml(),
        ]);

        $this->addTab('products_section', [
            'label'   => __('Related Products'),
            'content' => $this->getLayout()
                ->createBlock('Magenest\Blog\Block\Adminhtml\Post\Edit\Tab\Products')->toHtml(),
        ]);

        $this->addTab('comment_section', [
            'label'   => __('List Comments'),
            'content' => $this->getLayout()
                ->createBlock('Magenest\Blog\Block\Adminhtml\Post\Edit\Tab\Comment')->toHtml(),
        ]);

        return parent::_beforeToHtml();
    }
}
