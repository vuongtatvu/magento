<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Controller\Post;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\NotFoundException;

/**
 * Class Comment
 * @package Magenest\Blog\Controller\Post
 */
class Comment extends  \Magento\Framework\App\Action\Action
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
     * Comment constructor.
     * @param Context $context
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool
     */
    public function __construct(
        Context $context,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool
    ) {
        parent::__construct($context);
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheFrontendPool = $cacheFrontendPool;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     * @throws NotFoundException
     * @SuppressWarnings(PHPMD.ExitExpression)
     */
    public function execute()
    {
        $data = $this->_request->getParams();
        $data['comment'] = htmlentities($data['comment']);
        $data['name'] = htmlentities($data['name']);
        $model = $this->_objectManager->create('Magenest\Blog\Model\Comment');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            try {
                $data['created_at'] = date('d/m/Y, H:i:s');
                $model->addData($data);
                $model->save();
                $this->flushCache();
                return $resultRedirect->setUrl($data['path']);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath($data['path']);
            }
        }

        return $resultRedirect->setPath('*/*/*');
    }

    /**
     *  post comment to store page
     */
    public function flushCache(){
        $types = array(
            'config',
            'layout',
            'block_html',
            'full_page'
        );
        foreach ($types as $type) {
            $this->_cacheTypeList->cleanType($type);
        }
        foreach ($this->_cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }
    }
}
