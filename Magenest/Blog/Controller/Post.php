<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Controller;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magenest\Blog\Model\PostFactory;
use Magento\Framework\Registry;

/**
 * Class Post
 * @package Magenest\Blog\Controller
 */
abstract class Post extends Action
{
    /**
     * @var PostFactory
     */
    protected $postFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param StoreManagerInterface $storeManager
     * @param PostFactory           $authorFactory
     * @param Registry              $registry
     * @param Context               $context
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        PostFactory $authorFactory,
        Registry $registry,
        Context $context
    ) {
        $this->storeManager  = $storeManager;
        $this->postFactory   = $authorFactory;
        $this->registry      = $registry;
        $this->context       = $context;
        $this->resultFactory = $context->getResultFactory();;
        parent::__construct($context);
    }

    /**
     * @return \Magenest\Blog\Model\Post|boolean
     */
    protected function initModel()
    {
        $id = $this->getRequest()->getParam('id');
        if (!$id) {

            return false;
        }
        $post = $this->postFactory->create()->load($id);
        if (!$post->getId() || !$post->isStoreAllowed($this->storeManager->getStore()->getId())) {

            return false;
        }
        $this->registry->register('current_blog_post', $post);

        return $post;
    }
}
