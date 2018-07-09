<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Controller\Adminhtml\Post;

use Magenest\Blog\Controller\Adminhtml\Post;

/**
 * Class RelatedProductsGrid
 * @package Magenest\Blog\Controller\Adminhtml\Post
 */
class RelatedProductsGrid extends Post
{
    /**
     * @return void
     */
    public function execute()
    {
        $this->initModel();
        $this->_view->loadLayout()
            ->getLayout()
            ->getBlock('blog.post.tab.products')
            ->setProductsRelated($this->getRequest()->getPost('products_related'));
        $this->_view->renderLayout();
    }
}
