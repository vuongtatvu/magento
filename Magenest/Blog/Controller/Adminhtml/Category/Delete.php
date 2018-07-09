<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Controller\Adminhtml\Category;

use Magenest\Blog\Controller\Adminhtml\Category;

/**
 * Class Delete
 * @package Magenest\Blog\Controller\Adminhtml\Category
 */
class Delete extends Category
{
    /**
     * @return $this
     */
    public function execute()
    {
        $model = $this->initModel();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($model->getId()) {
            if($model->getId() == 1){
                $this->messageManager->addError(__("Can't delete the root category!"));
                return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
            }
            try {
                $model->delete();
                $this->messageManager->addSuccess(__('The category has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
            }
        } else {
            $this->messageManager->addError(__('This category no longer exists.'));

            return $resultRedirect->setPath('*/*/');
        }
    }
}
