<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Block\Post;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use Magenest\Blog\Model\Config;

/**
 * Class AbstractBlock
 * @package Magenest\Blog\Block\Post
 */
class AbstractBlock extends Template
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param Config   $config
     * @param Registry $registry
     * @param Context  $context
     */
    public function __construct(
        Config $config,
        Registry $registry,
        Context $context
    ) {
        $this->config = $config;
        $this->registry = $registry;
        $this->context = $context;
        parent::__construct($context);
    }

    /**
     * @param object|null $post
     * @return \Magenest\Blog\Block\Post\Meta
     */
    public function getPostMetaHeader($post = null)
    {
        /** @var \Magenest\Blog\Block\Post\Meta $block */
        $block = $this->getLayout()->createBlock('Magenest\Blog\Block\Post\Meta');
        $block->setTemplate('post/meta/header.phtml');
        if ($post) {
            $block->setData('post', $post);
        }

        return $block;
    }

    /**
     * @param object|null $post
     * @return \Magenest\Blog\Block\Post\Meta
     */
    public function getPostMetaFooter($post = null)
    {
        /** @var \Magenest\Blog\Block\Post\Meta $block */
        $block = $this->getLayout()->createBlock('Magenest\Blog\Block\Post\Meta');
        $block->setTemplate('post/meta/footer.phtml');
        if ($post) {
            $block->setData('post', $post);
        }

        return $block;
    }
}
