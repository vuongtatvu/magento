<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Block\Catalog;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Magenest\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;
use Magenest\Blog\Model\Config;
use Magenest\Blog\Model\Url;

/**
 * Class RelatedPosts
 * @package Magenest\Blog\Block\Catalog
 */
class RelatedPosts extends Template
{
    /**
     * @var PostCollectionFactory
     */
    protected $postCollectionFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Url
     */
    protected $url;

    /**
     * RelatedPosts constructor.
     * @param PostCollectionFactory $postCollectionFactory
     * @param Config $config
     * @param Url $url
     * @param Registry $registry
     * @param Context $context
     */
    public function __construct(
        PostCollectionFactory $postCollectionFactory,
        Config $config,
        Url $url,
        Registry $registry,
        Context $context
    ) {
        $this->postCollectionFactory = $postCollectionFactory;
        $this->config = $config;
        $this->url = $url;
        $this->registry = $registry;
        parent::__construct($context);
        $this->setTabTitle();
    }

    /**
     * Set tab title
     *
     * @return void
     */
    public function setTabTitle()
    {
        $size = $this->getCollection()->count();
        $title = $size
            ? __('Related Posts %1', '<span class="counter">' . $size . '</span>')
            : '';
        $this->setTitle($title);
    }

    /**
     * @return \Magento\Catalog\Model\Product|false
     */
    public function getProduct()
    {

        return $this->registry->registry('current_product');
    }

    /**
     * @return \Magenest\Blog\Model\ResourceModel\Post\Collection|\Magenest\Blog\Model\Post[]
     */
    public function getCollection()
    {
        $collection = $this->postCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addVisibilityFilter()
            ->addStoreFilter($this->_storeManager->getStore()->getId())
            ->setOrder('created_at', 'desc');
        if ($product = $this->getProduct()) {
            $collection->addRelatedProductFilter($product);
        }

        return $collection;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {

        return $this->config;
    }

    /**
     * @return string
     */
    public function getRssUrl()
    {

        return $this->url->getRssUrl($this->getCategory());
    }
}
