<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Block\Tag;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class View
 * @package Magenest\Blog\Block\Tag
 */
class View extends Template
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
     * @param Registry $registry
     * @param Context  $context
     * @param array    $data
     */
    public function __construct(
        Registry $registry,
        Context $context,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->context = $context;
        parent::__construct($context, $data);
    }

    /**
     * @return $this
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $tag = $this->getTag();
        if (!$tag) {
            return $this;
        }
        $this->pageConfig->getTitle()->set(__('Tag: %1', $tag->getName()));
        if ($tag && ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs'))) {
            $breadcrumbs->addCrumb('home', [
                'label' => __('Home'),
                'title' => __('Go to Home Page'),
                'link'  => $this->context->getUrlBuilder()->getBaseUrl(),
            ]);

            $breadcrumbs->addCrumb('blog', [
                'label' => __('Blog'),
                'title' => __('Blog'),
            ]);

            $breadcrumbs->addCrumb($tag->getId(), [
                'label' => __('Tag: %1', $tag->getName()),
                'title' => __('Tag: %1', $tag->getName()),
            ]);
        }

        return $this;
    }

    /**
     * @return \Magenest\Blog\Model\Tag
     */
    public function getTag()
    {

        return $this->registry->registry('current_blog_tag');
    }
}
