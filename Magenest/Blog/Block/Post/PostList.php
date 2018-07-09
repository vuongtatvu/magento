<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Block\Post;

use Magento\Framework\DataObject\IdentityInterface;
use Magenest\Blog\Model\Config;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use Magenest\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;

/**
 * Class PostList
 * @package Magenest\Blog\Block\Post
 */
class PostList extends AbstractBlock implements IdentityInterface
{
    /**
     * @var string
     */
    protected $defaultToolbarBlock = 'Magenest\Blog\Block\Post\PostList\Toolbar';

    /**
     * @var PostCollectionFactory
     */
    protected $postCollectionFactory;

    /**
     * @var \Magenest\Blog\Model\ResourceModel\Post\Collection
     */
    protected $collection;

    /**
     * @param PostCollectionFactory $postCollectionFactory
     * @param Config                $config
     * @param Registry              $registry
     * @param Context               $context
     */
    public function __construct(
        PostCollectionFactory $postCollectionFactory,
        Config $config,
        Registry $registry,
        Context $context
    ) {
        $this->postCollectionFactory = $postCollectionFactory;

        parent::__construct($config, $registry, $context);
    }

    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $toolbar = $this->getToolbarBlock();

        // called prepare sortable parameters
        $collection = $this->getPostCollection();

        // use sortable parameters
        $orders = $this->getAvailableOrders();
        if ($orders) {
            $toolbar->setAvailableOrders($orders);
        }

        $sort = $this->getSortBy();
        if ($sort) {
            $toolbar->setDefaultOrder($sort);
        }

        $dir = $this->getDefaultDirection();
        if ($dir) {
            $toolbar->setDefaultDirection($dir);
        }
        $toolbar->setCollection($collection);
        $this->setChild('toolbar', $toolbar);
        $this->setCollection($toolbar->getCollection());
        $this->getPostCollection()->load();
        return parent::_beforeToHtml();

    }

    /**
     * @return PostList\Toolbar
     */
    public function getToolbarBlock()
    {
        $blockName = $this->getToolbarBlockName();
        if ($blockName) {
            $block = $this->getLayout()->getBlock($blockName);
            if ($block) {
                return $block;
            }
        }
        $block = $this->getLayout()->createBlock($this->defaultToolbarBlock, uniqid(microtime()));

        return $block;
    }

    /**
     * @return string
     */
    public function getAdditionalHtml()
    {

        return $this->getChildHtml('additional');
    }

    /**
     * @return string
     */
    public function getToolbarHtml()
    {

        return $this->getChildHtml('toolbar');
    }

    /**
     * @param \Magenest\Blog\Model\ResourceModel\Post\Collection $collection
     * @return $this
     */
    public function setCollection($collection)
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * Return identifiers for post content.
     *
     * @return array
     */
    public function getIdentities()
    {
        $identities = [];
        foreach ($this->getPostCollection() as $post) {
            $identities = array_merge($identities, $post->getIdentities());
        }

        return $identities;
    }

    /**
     * Retrieve current category model object.
     *
     * @return \Magenest\Blog\Model\Category
     */
    public function getCategory()
    {

        return $this->registry->registry('current_blog_category');
    }

    /**
     * @return \Magenest\Blog\Model\Tag
     */
    public function getTag()
    {

        return $this->registry->registry('current_blog_tag');
    }

    /**
     * @return \Magenest\Blog\Model\Author
     */
    public function getAuthor()
    {

        return $this->registry->registry('current_blog_author');
    }

    /**
     * @return string
     */
    public function getSearchQuery()
    {

        return $this->registry->registry('current_blog_query');
    }

    /**
     * @return \Magenest\Blog\Model\ResourceModel\Post\Collection
     */
    public function getPostCollection()
    {
        $toolbar = $this->getToolbarBlock();
        if (empty($this->collection)) {
            $collection = $this->postCollectionFactory->create()
                ->addAttributeToSelect(['name', 'featured_image', 'short_content', 'content', 'url_key'])
                ->addStoreFilter($this->context->getStoreManager()->getStore()->getId())
                ->addVisibilityFilter();

            if ($category = $this->getCategory()) {
                $collection->addCategoryFilter($category);
            } elseif ($tag = $this->getTag()) {
                $collection->addTagFilter($tag);
            } elseif ($author = $this->getAuthor()) {
                $collection->addAuthorFilter($author);
            } elseif ($q = $this->getRequest()->getParam('q')) {
                $collection->addSearchFilter($q);
            }

            $collection->setCurPage($this->getCurrentPage());

            $limit = (int)$toolbar->getLimit();
            if ($limit) {
                $collection->setPageSize($limit);
            }

            $page = (int)$toolbar->getCurrentPage();
            if ($page) {
                $collection->setCurPage($page);
            }

            if ($order = $toolbar->getCurrentOrder()) {
                $collection->setOrder($order, $toolbar->getCurrentDirection());
            }

            $this->collection = $collection;
        }

        return $this->collection;
    }
}
