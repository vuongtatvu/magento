<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Block\Author;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class View
 * @package Magenest\Blog\Block\Author
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
        $author = $this->getAuthor();
        $this->pageConfig->getTitle()->set(__('Author: %1', $author->getName()));
        if ($author && ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs'))) {
            $breadcrumbs->addCrumb('home', [
                'label' => __('Home'),
                'title' => __('Go to Home Page'),
                'link'  => $this->context->getUrlBuilder()->getBaseUrl(),
            ]);

            $breadcrumbs->addCrumb('blog', [
                'label' => __('Blog'),
                'title' => __('Blog'),
            ]);

            $breadcrumbs->addCrumb($author->getId(), [
                'label' => __('Author: %1', $author->getName()),
                'title' => __('Author: %1', $author->getName()),
            ]);
        }

        return $this;
    }

    /**
     * @return \Magenest\Blog\Model\Author
     */
    public function getAuthor()
    {

        return $this->registry->registry('current_blog_author');
    }
}
