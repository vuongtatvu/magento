<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Block\Adminhtml\Category\Edit;

/**
 * Class Tabs
 * @package Magenest\Blog\Block\Adminhtml\Category\Edit
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setDestElementId('edit_form');
    }

    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->addTab('general_section', [
            'label'   => __('General Information'),
            'content' => $this->getLayout()
                ->createBlock('\Magenest\Blog\Block\Adminhtml\Category\Edit\Tab\General')->toHtml(),
        ]);

        $this->addTab('meta_section', [
            'label'   => __('Search Engine Optimization'),
            'content' => $this->getLayout()
                ->createBlock('\Magenest\Blog\Block\Adminhtml\Category\Edit\Tab\Meta')->toHtml(),
        ]);

        return parent::_beforeToHtml();
    }
}
