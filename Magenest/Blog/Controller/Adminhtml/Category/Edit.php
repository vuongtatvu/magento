<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Controller\Adminhtml\Category;

use Magento\Framework\Controller\ResultFactory;
use Magenest\Blog\Controller\Adminhtml\Category;

/**
 * Class Edit
 * @package Magenest\Blog\Controller\Adminhtml\Category
 */
class Edit extends Category
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var  $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $id = $this->getRequest()->getParam('id');
        $model = $this->initModel();
        if ($id && !$model->getId()) {
            $this->messageManager->addError(__('This category no longer exists.'));
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        $this->initPage($resultPage)->getConfig()->getTitle()->prepend($id ? $model->getName() : __('New Category'));

        return $resultPage;
    }
}
