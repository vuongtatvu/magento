<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Block\Category;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Magenest\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;
use Magenest\Blog\Model\Config;
use Magenest\Blog\Model\Url;

/**
 * Class Rss
 * @package Magenest\Blog\Block\Category
 */
class Rss extends Template
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
     * @param PostCollectionFactory $postCollectionFactory
     * @param Config                $config
     * @param Url                   $url
     * @param Registry              $registry
     * @param Context               $context
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
    }

    /**
     * @return \Magenest\Blog\Model\Category|false
     */
    public function getCategory()
    {

        return $this->registry->registry('current_blog_category');
    }

    /**
     * @return \Magenest\Blog\Model\Post[]
     */
    public function getCollection()
    {
        $collection = $this->postCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addVisibilityFilter()
            ->setOrder('created_at')
            ->setPageSize(10);
        if ($category = $this->getCategory()) {
            $collection->addCategoryFilter($category);
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
