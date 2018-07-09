<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Block\Category;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Magenest\Blog\Model\Category;
use Magenest\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;
use Magenest\Blog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magenest\Blog\Model\Config;

/**
 * Class View
 * @package Magenest\Blog\Block\Category
 */
class View extends Template implements IdentityInterface
{
    /**
     * @var PostCollectionFactory
     */
    protected $postCollectionFactory;

    /**
     * @var CategoryCollectionFactory
     */
    protected $categoryCollectionFactory;

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
     * @param PostCollectionFactory     $postCollectionFactory
     * @param CategoryCollectionFactory $categoryCollectionFactory
     * @param Config                    $config
     * @param Registry                  $registry
     * @param Context                   $context
     * @param array                     $data
     */
    public function __construct(
        PostCollectionFactory $postCollectionFactory,
        CategoryCollectionFactory $categoryCollectionFactory,
        Config $config,
        Registry $registry,
        Context $context,
        array $data = []
    ) {
        $this->postCollectionFactory = $postCollectionFactory;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->config = $config;
        $this->registry = $registry;
        $this->context = $context;

        parent::__construct($context, $data);
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $category = $this->getCategory();
        $title = $category ? $category->getName() : $this->config->getBlogName();
        $metaTitle = $category
            ? ($category->getMetaTitle() ? $category->getMetaTitle() : $category->getName())
            : $this->config->getBaseMetaTitle();
        $metaDescription = $category
            ? ($category->getMetaDescription() ? $category->getMetaDescription() : $category->getName())
            : $this->config->getBaseMetaDescription();
        $metaKeywords = $category
            ? ($category->getMetaKeywords() ? $category->getMetaKeywords() : $category->getName())
            : $this->config->getBaseMetaKeywords();
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
            ]);
            if ($category) {
                $ids = $category->getParentIds();

                $ids[] = 0;
                $parents = $this->categoryCollectionFactory->create()
                    ->addFieldToFilter('entity_id', $ids)
                    ->addNameToSelect()
                    ->excludeRoot()
                    ->setOrder('level', 'asc');

                /** @var \Magenest\Blog\Model\Category $cat */
                foreach ($parents as $cat) {
                    $breadcrumbs->addCrumb($cat->getId(), [
                        'label' => $cat->getName(),
                        'title' => $cat->getName(),
                        'link'  => $cat->getUrl(),
                    ]);
                }
                $breadcrumbs->addCrumb($category->getId(), [
                    'label' => $category->getName(),
                    'title' => $category->getName(),
                ]);
            }
        }

        return $this;
    }

    /**
     * @return \Magenest\Blog\Model\Category
     */
    public function getCategory()
    {

        return $this->registry->registry('current_blog_category');
    }

    /**
     * @return array
     */
    public function getIdentities()
    {
        if ($this->getCategory()) {
            return $this->getCategory()->getIdentities();
        }

        return [Category::CACHE_TAG];
    }
}
