<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Controller\Category;

use Magento\Framework\Controller\ResultFactory;
use Magenest\Blog\Controller\Category;

/**
 * Class Index
 * @package Magenest\Blog\Controller\Category
 */
class Index extends Category
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        return $resultPage;
    }
}
