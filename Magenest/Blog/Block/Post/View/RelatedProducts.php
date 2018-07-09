<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Block\Post\View;

use Magento\Catalog\Block\Product\Context;
use Magento\Framework\Registry;
use Magento\Catalog\Block\Product\AbstractProduct;

/**
 * Class RelatedProducts
 * @package Magenest\Blog\Block\Post\View
 */
class RelatedProducts extends AbstractProduct
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        $this->registry = $context->getRegistry();
        parent::__construct($context);
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getRelatedProducts()
    {

        return $this->getCurrentPost()->getRelatedProducts();
    }

    /**
     * @return \Magenest\Blog\Model\Post
     */
    public function getCurrentPost()
    {

        return $this->registry->registry('current_blog_post');
    }
}