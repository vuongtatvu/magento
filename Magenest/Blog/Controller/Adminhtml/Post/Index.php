<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Controller\Adminhtml\Post;

use Magento\Framework\Controller\ResultFactory;
use Magenest\Blog\Controller\Adminhtml\Post;

/**
 * Class Index
 * @package Magenest\Blog\Controller\Adminhtml\Post
 */
class Index extends Post
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $this->initPage($resultPage)
             ->getConfig()
             ->getTitle()
             ->prepend(__('All Posts'));

        return $resultPage;
    }
}
