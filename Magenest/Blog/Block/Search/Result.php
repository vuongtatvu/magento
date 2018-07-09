<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Block\Search;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Magenest\Blog\Model\Config;

/**
 * Class Result
 * @package Magenest\Blog\Block\Search
 */
class Result extends Template
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
     * @param array    $data
     */
    public function __construct(
        Config $config,
        Registry $registry,
        Context $context,
        array $data = []
    ) {
        $this->config = $config;
        $this->registry = $registry;
        $this->context = $context;
        parent::__construct($context, $data);
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $title = $metaTitle = __("Search results for: '%1'", $this->getRequest()->getParam('q'));
        $metaDescription = $this->config->getBaseMetaDescription();
        $metaKeywords = $this->config->getBaseMetaKeywords();
        $this->pageConfig->getTitle()->set($metaTitle);
        $this->pageConfig->setDescription($metaDescription);
        $this->pageConfig->setKeywords($metaKeywords);

        /** @var \Magento\Theme\Block\Html\Title $pageMainTitle */
        $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
        if ($pageMainTitle) {
            $pageMainTitle->setPageTitle($title);
        }
        /** @var \Magento\Theme\Block\Html\Breadcrumbs $breadcrumbs */
        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbs->addCrumb('home', [
                'label' => __('Home'),
                'title' => __('Go to Home Page'),
                'link'  => $this->context->getUrlBuilder()->getBaseUrl(),
            ])->addCrumb('blog', [
                'label' => $this->config->getBlogName(),
                'title' => $this->config->getBlogName(),
                'link'  => $this->config->getBaseUrl()
            ])->addCrumb('search', [
                'label' => $title,
                'title' => $title
            ]);
        }

        return $this;
    }

    /**
     * @return \Magenest\Blog\Model\ResourceModel\Post\Collection
     */
    public function getPostCollection()
    {

        return $this->getChildBlock('blog.post.list')->getPostCollection();
    }
}
