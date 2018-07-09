<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 *
 * Magenest_Blog extension
 * NOTICE OF LICENSE
 *
 * @category Magenest
 * @package  Magenest_Blog
 * @author   <ThaoPV> thaopw@gmail.com
 */
namespace Magenest\Blog\Plugin;

use Magento\Framework\View\Element\Template;

class Topmenu extends Template
{
    /**
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function afterGetHtml(\Magento\Theme\Block\Html\Topmenu $subject, $result)
    {
        $result .= '<li class="level0 nav-10 level-top parent ui-menu-item"><a href="'.$this->getUrl('blog').'" class="level-top ui-corner-all" role="presentation">Blog</a></li>';

        return $result;
    }
}
