<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Controller\Category;

use Magenest\Blog\Controller\Category;

/**
 * Class Rss
 * @package Magenest\Blog\Controller\Category
 */
class Rss extends Category
{
    /**
     * @return void
     */
    public function execute()
    {
        $rss = $this->_view->getLayout()->createBlock('Magenest\Blog\Block\Category\Rss')
            ->setTemplate('category/rss.phtml')
            ->toHtml();

        $this->getResponse()
            ->setHeader('Content-type', 'text/xml')
            ->setBody($rss);
    }
}
