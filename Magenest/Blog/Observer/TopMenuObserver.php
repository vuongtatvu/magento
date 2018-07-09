<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magenest\Blog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Framework\Data\Tree\Node as TreeNode;
use Magenest\Blog\Model\Config;

/**
 * Class TopMenuObserver
 * @package Magenest\Blog\Observer
 */
class TopMenuObserver implements ObserverInterface
{
    /**
     * @var CategoryCollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param Config                    $config
     * @param CategoryCollectionFactory $categoryCollectionFactory
     */
    public function __construct(
        Config $config,
        CategoryCollectionFactory $categoryCollectionFactory
    ) {
        $this->config = $config;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     * {@inheritdoc}
     *
     * @param EventObserver $observer
     */
    public function execute(EventObserver $observer)
    {
        if (!$this->config->isDisplayInMenu()) {
            return;
        }
        /** @var \Magento\Framework\Data\Tree\Node $menu */
        $menu = $observer->getData('menu');

        $categories = $this->categoryCollectionFactory->create()
            ->addAttributeToSelect(['name', 'url_key'])
            ->excludeRoot()
            ->addVisibilityFilter();
        $tree = $categories->getTree();
        $rootNode = new TreeNode(
            [
                'id'   => 'blog-node-root',
                'name' => $this->config->getMenuTitle(),
                'url'  => $this->config->getBaseUrl()
            ],
            'id',
            $menu->getTree(),
            null
        );
        //@todo find correct way to add class
        $menu->setPositionClass('blog-mx' . $menu->getPositionClass());
        $menu->addChild($rootNode);

        foreach ($tree as $category) {
            if (isset($tree[$category->getParentId()])) {
                $parentNode = $tree[$category->getParentId()]->getData('node');
            } else {
                $parentNode = $rootNode;
            }

            $node = new TreeNode(
                [
                    'id'   => 'blog-node-' . $category->getId(),
                    'name' => $category->getName(),
                    'url'  => $category->getUrl()
                ],
                'id',
                $menu->getTree(),
                $parentNode
            );

            if ($parentNode) {
                $parentNode->addChild($node);
            } else {
                $menu->addChild($node);
            }
            $category->setData('node', $node);
        }
    }
}
