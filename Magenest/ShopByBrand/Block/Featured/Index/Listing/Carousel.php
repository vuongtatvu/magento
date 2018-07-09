<?php
/**
 * Created by PhpStorm.
 * User: duccanh
 * Date: 26/10/2016
 * Time: 09:06
 */
namespace Magenest\ShopByBrand\Block\Featured\Index\Listing;

/**
 * Class Carousel
 *
 * @package Magenest\ShopByBrand\Block\Featured\Index\Listing
 */
class Carousel extends \Magenest\ShopByBrand\Block\Featured\Index\Listing
{
    public function prepareTemplate()
    {
        $sidebar=$this->_scopeConfig->getValue('shopbybrand/brandpage/featured_brand');
        if (strpos($sidebar, '1')!== false) {
            $this->setTemplate('Magenest_ShopByBrand::featured/index/carousel.phtml');
        }
    }
}
