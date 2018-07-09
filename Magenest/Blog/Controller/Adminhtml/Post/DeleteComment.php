<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\Blog\Controller\Adminhtml\Post;
use Magento\Backend\App\Action;

/**
 * Class MassDelete
 * @package Magenest\Blog\Controller\Adminhtml\Post
 */
class DeleteComment extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $_cacheTypeList;

    /**
     * @var \Magento\Framework\App\Cache\Frontend\Pool
     */
    protected $_cacheFrontendPool;

    /**
     * DeleteComment constructor.
     * @param Action\Context $context
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool
    ) {
        parent::__construct($context);
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheFrontendPool = $cacheFrontendPool;
    }

    /**
     * @return $this
     */
    public function execute()
    {
        $collection = $this->getRequest()->getParams();
        $resultRedirect = $this->resultRedirectFactory->create();
        $model = $this->_objectManager->create('Magenest\Blog\Model\Comment');
        try {
            $model ->load($collection['comment_id']);
            $postId = $model->getPostId();
            $model->delete();
            $this->flushCache();
            $this->messageManager->addSuccessMessage(__('This comment have been deteled.'));
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->_getSession()->addException($e, __('Something went wrong while delete the post(s).'));
        }

        return $resultRedirect->setPath('*/*/edit', ['id' => $postId]);
    }

    /**
     * flush store page cache
     */
    public function flushCache(){
        $types = array(
            'config',
            'layout',
            'block_html',
            'full_page',
        );
        foreach ($types as $type) {
            $this->_cacheTypeList->cleanType($type);
        }
        foreach ($this->_cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }
    }
}
