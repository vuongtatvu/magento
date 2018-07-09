<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Controller\Adminhtml\Post;

use Magento\Framework\Controller\ResultFactory;
use Magenest\Blog\Controller\Adminhtml\Post;

/**
 * Class Edit
 * @package Magenest\Blog\Controller\Adminhtml\Post
 */
class Edit extends Post
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $id = $this->getRequest()->getParam('id');
        $model = $this->initModel();
        if ($id && ! is_array($id) && !$model->getId()) {
            $this->messageManager->addError(__('This post no longer exists.'));
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        $this->initPage($resultPage)->getConfig()->getTitle()->prepend(
            $model->getName() ? $model->getName() : __('New Post')
        );

        return $resultPage;
    }
}
