<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magenest\Blog\Model\PostFactory;
use Magento\Framework\Registry;
use Magento\Backend\App\Action\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Backend\Helper\Js as JsHelper;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

/**
 * Class Post
 * @package Magenest\Blog\Controller\Adminhtml
 */
abstract class Post extends Action
{
    /**
     * @var PostFactory
     */
    protected $postFactory;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * @var JsHelper
     */
    protected $jsHelper;

    /**
     * @param PostFactory           $authorFactory
     * @param StoreManagerInterface $storeManager
     * @param JsonFactory           $jsonFactory
     * @param JsHelper              $jsHelper
     * @param Registry              $registry
     * @param TimezoneInterface     $localeDate
     * @param Context               $context
     */
    public function __construct(
        PostFactory $authorFactory,
        StoreManagerInterface $storeManager,
        JsonFactory $jsonFactory,
        JsHelper $jsHelper,
        Registry $registry,
        TimezoneInterface $localeDate,
        Context $context
    ) {
        $this->postFactory  = $authorFactory;
        $this->storeManager = $storeManager;
        $this->jsonFactory  = $jsonFactory;
        $this->jsHelper     = $jsHelper;
        $this->registry     = $registry;
        $this->localeDate   = $localeDate;
        $this->context      = $context;

        $this->resultFactory = $context->getResultFactory();

        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     * @param \Magento\Backend\Model\View\Result\Page\Interceptor $resultPage
     * @return \Magento\Backend\Model\View\Result\Page\Interceptor
     */
    protected function initPage($resultPage)
    {
        $resultPage->setActiveMenu('Magenest_Blog::blog');
        $resultPage->getConfig()->getTitle()->prepend(__('Magenest Blog MX'));
        $resultPage->getConfig()->getTitle()->prepend(__('Posts'));

        return $resultPage;
    }

    /**
     * @return \Magenest\Blog\Model\Post
     */
    public function initModel()
    {
        $model = $this->postFactory->create();
        $id = $this->getRequest()->getParam('id');
        
        if ($id && ! is_array($id)) {
            $model->load($id);
        }

        $this->registry->register('current_model', $model);

        return $model;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {

        return $this->context->getAuthorization()->isAllowed('Magenest_Blog::blog_post');
    }
}
