<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Controller\Adminhtml\Category;

use Magento\Framework\Controller\ResultFactory;
use Magenest\Blog\Controller\Adminhtml\Category;

/**
 * Class Index
 * @package Magenest\Blog\Controller\Adminhtml\Category
 */
class Index extends Category
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->context->getResultFactory()->create(ResultFactory::TYPE_PAGE);
        $this->initPage($resultPage)
            ->getConfig()->getTitle()->prepend(__('Categories'));
        $this->_addContent($resultPage->getLayout()
            ->createBlock('\Magenest\Blog\Block\Adminhtml\Category'));

        return $resultPage;
    }
}
