<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Controller\Adminhtml\Author;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Delete
 * @package Magenest\Blog\Controller\Adminhtml\Author
 */
class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magenest\Blog\Model\AuthorFactory
     */
    protected $author;

    /**
     * Delete constructor.
     * @param Context $context
     * @param \Magenest\Blog\Model\AuthorFactory $authorFactory
     * @param \Psr\Log\LoggerInterface $loggerInterface
     */
    public function __construct(
        Context $context,
        \Magenest\Blog\Model\AuthorFactory $authorFactory,
        \Psr\Log\LoggerInterface $loggerInterface
    ) {
        parent::__construct($context);
        $this->logger = $loggerInterface;
        $this->author = $authorFactory;
    }

    /**
     * delete attributes
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->author->create();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            $model->load($id);
            $model->delete();
            $this->messageManager->addSuccessMessage(__('The author has been deleted.'));
            return $resultRedirect->setPath('blog/author/index', ['_current'=>true]);
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage(
                $e,
                __('We can\'t delete the template right now.')
            );
            $this->_redirect('blog/author/edit', ['id' => $id, '_current' => true]);
            return $resultRedirect->setPath('blog/author/edit', ['id' => $id, '_current' => true]);
        }

        return $resultRedirect->setPath('*/*/');
    }

    protected function _isAllowed()
    {

        return $this->_authorization->isAllowed('Magenest_Blog::blog_author');
    }
}
