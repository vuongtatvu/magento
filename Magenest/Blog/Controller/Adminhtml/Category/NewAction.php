<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Controller\Adminhtml\Category;

use Magenest\Blog\Controller\Adminhtml\Category;

/**
 * Class NewAction
 * @package Magenest\Blog\Controller\Adminhtml\Category
 */
class NewAction extends Category
{
    /**
     * @return $this
     */
    public function execute()
    {

        return $this->resultRedirectFactory->create()->setPath('*/*/edit');
    }
}
