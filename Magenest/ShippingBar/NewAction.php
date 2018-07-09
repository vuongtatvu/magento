<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Controller\Adminhtml\Author;

use Magenest\Blog\Controller\Adminhtml\Author as AbstractAuthor;

/**
 * Class NewAction
 * @package Magenest\Blog\Controller\Adminhtml\Author
 */
class NewAction extends AbstractAuthor
{
    /**
     * forward to edit
     *
     * @return $this
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Forward $resultForward */
        $resultForward = $this->resultForwardFactory->create();
        
        return $resultForward->forward('edit');
    }
}
