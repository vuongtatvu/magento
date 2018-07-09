<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Block\Post\View;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use Magenest\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;

/**
 * Class RelatedPosts
 * @package Magenest\Blog\Block\Post\View
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
     * @param PostCollectionFactory $postCollectionFactory
     * @param Registry              $registry
     * @param Context               $context
     */
    public function __construct(
        PostCollectionFactory $postCollectionFactory,
        Registry $registry,
        Context $context
    ) {
        $this->postCollectionFactory = $postCollectionFactory;
        $this->registry = $registry;

        parent::__construct($context);
    }

    /**
     * @return \Magenest\Blog\Model\ResourceModel\Post\Collection
     */
    public function getPostCollection()
    {
        $tags = $this->getCurrentPost()->getTagIds();
        $collection = $this->postCollectionFactory->create()
            ->addTagFilter($tags)
            ->addFieldToFilter('entity_id', ['neq' => $this->getCurrentPost()->getId()])
            ->addVisibilityFilter()
            ->addAttributeToSelect('*');

        return $collection;
    }

    /**
     * @return \Magenest\Blog\Model\Post
     */
    public function getCurrentPost()
    {

        return $this->registry->registry('current_blog_post');
    }
}
