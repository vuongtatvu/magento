<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_ShopByBrand extension
 * NOTICE OF LICENSE
 *
 * @category Magenest
 * @package  Magenest_ShopByBrand
 * @author   Chienbigstar <chienbigstar@gmail.com>
 */
namespace Magenest\ShopByBrand\Block\Brand\Index\Listing;

/**
 * Class Carousel
 *
 * @package Magenest\ShopByBrand\Block\Brand\Index\Listing
 */
class Carousel extends \Magenest\ShopByBrand\Block\Brand\Index\Listing
{
    public function prepareTemplate()
    {
        $sidebar=$this->_scopeConfig->getValue('shopbybrand/brandstyle/template');
        if (strpos($sidebar, '1')!== false) {
            $this->setTemplate('Magenest_ShopByBrand::brand/index/carousel.phtml');
        }
    }
}
