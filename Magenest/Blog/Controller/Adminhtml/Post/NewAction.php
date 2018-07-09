<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Controller\Adminhtml\Post;

use Magenest\Blog\Controller\Adminhtml\Post;

/**
 * Class NewAction
 * @package Magenest\Blog\Controller\Adminhtml\Post
 */
class NewAction extends Post
{
    /**
     * @return $this
     */
    public function execute()
    {

        return $this->resultRedirectFactory->create()->setPath('*/*/edit');
    }
}
