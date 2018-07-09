<?php
/**
 * Created by PhpStorm.
 * User: duccanh
 * Date: 09/06/2016
 * Time: 11:51
 */
namespace Magenest\ShopByBrand\Block\Brand\SideBar;

/**
 * Class Carousel
 *
 * @package Magenest\ShopByBrand\Block\Brand\SideBar
 */
class Carousel extends \Magenest\ShopByBrand\Block\Brand\SideBar
{
    /**
     * PrepareTemplate
     */
    public function prepareTemplate()
    {
        $sidebar=$this->_scopeConfig->getValue('shopbybrand/sidebar/template');
        if ($sidebar=='3' || $sidebar=='4') {
            $this->setTemplate('Magenest_ShopByBrand::sidebar/sidebar_carousel.phtml');
        }
    }

    /**
     * @return bool
     */
}
