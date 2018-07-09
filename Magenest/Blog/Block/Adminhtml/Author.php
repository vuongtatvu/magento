<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

/**
 * Class Author
 * @package Magenest\Blog\Block\Adminhtml
 */
class Author extends Container
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_author';
        $this->_blockGroup = 'Magenest_Blog';
        parent::_construct();
    }
}
