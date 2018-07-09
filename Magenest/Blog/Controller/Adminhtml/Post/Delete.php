<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Controller\Adminhtml\Post;

use Magenest\Blog\Controller\Adminhtml\Post;

/**
 * Class Delete
 * @package Magenest\Blog\Controller\Adminhtml\Post
 */
class Delete extends Post
{
    /**
     * @return $this
     */
    public function execute()
    {
        $model = $this->initModel();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($model->getId()) {
            try {
                $model->delete();
                $this->messageManager->addSuccess(__('The post has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
            }
        } else {
            $this->messageManager->addError(__('This post no longer exists.'));

            return $resultRedirect->setPath('*/*/');
        }
    }
}
