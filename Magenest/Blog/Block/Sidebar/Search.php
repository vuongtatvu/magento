<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Block\Sidebar;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magenest\Blog\Model\Url;

/**
 * Class Search
 * @package Magenest\Blog\Block\Sidebar
 */
class Search extends Template
{
    /**
     * @var Url
     */
    protected $url;

    /**
     * @param Url     $url
     * @param Context $context
     * @param array   $data
     */
    public function __construct(
        Url $url,
        Context $context,
        array $data = []
    ) {
        $this->url = $url;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getSearchUrl()
    {

        return $this->url->getSearchUrl();
    }
}
