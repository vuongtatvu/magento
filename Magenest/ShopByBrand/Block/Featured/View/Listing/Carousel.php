<?php
/**
 * Created by PhpStorm.
 * User: duccanh
 * Date: 26/10/2016
 * Time: 09:06
 */
namespace Magenest\ShopByBrand\Block\Featured\View\Listing;

/**
 * Class Carousel
 *
 * @package Magenest\ShopByBrand\Block\Featured\View\Listing
 */
class Carousel extends \Magenest\ShopByBrand\Block\Featured\View\Listing
{
    public function prepareTemplate()
    {
        $sidebar=$this->_scopeConfig->getValue('shopbybrand/brandpage/featured_product');
        if (strpos($sidebar, '1')!== false) {
            $this->setTemplate('Magenest_ShopByBrand::featured/view/carousel.phtml');
        }
    }
}
