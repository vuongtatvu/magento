<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Model\Post\PostList;

/**
 * Class ToolbarBlock
 * @package Magenest\Blog\Model\Post\PostList
 */
class ToolbarBlock
{

    /**
     * Request
     *
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @param \Magento\Framework\App\Request\Http $request
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $request
    ) {
        $this->request = $request;
    }

    /**
     * Get sort order
     *
     * @return string|bool
     */
    public function getOrder()
    {

        return $this->request->getParam('kb_article_list_order');
    }

    /**
     * Get sort direction
     *
     * @return string|bool
     */
    public function getDirection()
    {

        return $this->request->getParam('kb_article_list_dir');
    }

    /**
     * Get sort mode
     *
     * @return string|bool
     */
    public function getMode()
    {

        return $this->request->getParam('kb_article_list_mode');
    }

    /**
     * Get products per page limit
     *
     * @return string|bool
     */
    public function getLimit()
    {

        return $this->request->getParam('kb_article_list_limit');
    }
    /**
     * Return current page from request
     *
     * @return int
     */
    public function getCurrentPage()
    {
        $page = (int) $this->request->getParam('p');

        return $page ? $page : 1;
    }
}
