<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Controller\Adminhtml\Author;

use Magenest\Blog\Controller\Adminhtml\Author as AbstractAuthor;

/**
 * Class MassDelete
 * @package Magenest\Blog\Controller\Adminhtml\Author
 */
class MassDelete extends AbstractAuthor
{
    /**
     * @return mixed
     */
    public function execute()
    {
        $collections = $this->_filter->getCollection($this->_collectionFactory->create());
        $totals = 0;
        try {
            foreach ($collections as $item) {
            /** @var \Magenest\Blog\Model\Author $item */
                $item->delete();
                $totals++;
            }

            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deteled.', $totals));
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->_getSession()->addException($e, __('Something went wrong while delete the post(s).'));
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        
        return $resultRedirect->setPath('*/*/');
    }

    protected function _isAllowed()
    {

        return $this->_authorization->isAllowed('Magenest_Blog::blog_author');
    }
}
