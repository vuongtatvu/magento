<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Controller\Adminhtml\Category;

use Magenest\Blog\Controller\Adminhtml\Category;

/**
 * Class Save
 * @package Magenest\Blog\Controller\Adminhtml\Category
 */
class Save extends Category
{
    /**
     * @return $this|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data = $this->getRequest()->getParams()) {
            $model = $this->initModel();
            if (!$model->getId() && $id) {
                $this->messageManager->addError(__('This category no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
            $model->addData($data);
            try {
                $model->save();
                $this->messageManager->addSuccess(__('Category was successfully saved'));
                $this->context->getSession()->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }

                return $this->context->getResultRedirectFactory()->create()->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());

                return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            }
        } else {
            $resultRedirect->setPath('*/*/');
            $this->messageManager->addError('No data to save.');

            return $resultRedirect;
        }
    }
}
