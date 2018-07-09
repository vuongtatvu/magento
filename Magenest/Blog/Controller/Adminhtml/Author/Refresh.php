<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Controller\Adminhtml\Version;

/**
 * Class Refresh
 * @package Magenest\Blog\Controller\Adminhtml\Version
 */
class Refresh extends \Magento\Backend\App\Action
{
    /**
     * execute
     */
    public function execute()
    {
        $this->messageManager->addSuccessMessage('Refesh success !');
        $this->getResponse()->setRedirect($this->_redirect->getRedirectUrl($this->getUrl('*')));
        $this->_redirect('blog/*/');

        return;
    }

    protected function _isAllowed()
    {

        return $this->_authorization->isAllowed('Magenest_Blog::blog_author');
    }
}
