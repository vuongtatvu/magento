<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Magenest\Blog\Block\Sidebar;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Magenest\Blog\Model\ResourceModel\Tag\CollectionFactory as TagCollectionFactory;

/**
 * Class TagCloud
 * @package Magenest\Blog\Block\Sidebar
 */
class TagCloud extends Template
{
    /**
     * @var TagCollectionFactory
     */
    protected $tagCollectionFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var \Magenest\Blog\Model\ResourceModel\Tag\Collection
     */
    protected $collection;


    /**
     * @var \Magenest\Blog\Model\TagPostFactory
     */
    protected $_tagPostFactory;


    /**
     * TagCloud constructor.
     * @param \Magenest\Blog\Model\TagPostFactory $tagPostFactory
     * @param TagCollectionFactory $tagCollectionFactory
     * @param Registry $registry
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        \Magenest\Blog\Model\TagPostFactory $tagPostFactory,
        TagCollectionFactory $tagCollectionFactory,
        Registry $registry,
        Context $context,
        array $data = []
    )
    {
        $this->_tagPostFactory = $tagPostFactory;
        $this->tagCollectionFactory = $tagCollectionFactory;
        $this->registry = $registry;
        $this->context = $context;

        parent::__construct($context, $data);
    }

    /**
     * @return \Magenest\Blog\Model\ResourceModel\Tag\Collection
     */
    public function getCollection()
    {
        if (!$this->collection) {
            $this->collection = $this->tagCollectionFactory->create()
                ->joinPopularity();
        }

        return $this->collection;
    }

    /**
     * @param $tagId
     * @return bool
     */
    public function getEmptyTag($tagId)
    {
        $tags = $this->_tagPostFactory->create()->getCollection()->addFieldToFilter('tag_id', $tagId);
        if ($tags->count() > 0) {
            foreach ($tags as $tag) {
                $post = \Magento\Framework\App\ObjectManager::getInstance()
                    ->create('Magenest\Blog\Model\Post')->load($tag->getPostId());
                if ($post->getStatus() == 2) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @return int
     */
    public function getMaxPopularity()
    {
        $max = 0;
        foreach ($this->getCollection() as $tag) {
            if ($tag->getPopularity() > $max) {
                $max = $tag->getPopularity();
            }
        }

        return $max;
    }
}
