<?php
/**
 * Created by PhpStorm.
 * User: duccanh
 * Date: 27/10/2016
 * Time: 14:53
 */
namespace Magenest\ShopByBrand\Block\Category\Index\Listing;

/**
 * Class Categories
 *
 * @package Magenest\ShopByBrand\Block\Category\Index\Listing
 */
class Categories extends \Magenest\ShopByBrand\Block\Category\Index\Listing
{
    public function prepareTemplate()
    {
        $sidebar=$this->_scopeConfig->getValue('shopbybrand/brandpage/categories_brand');
        if (strpos($sidebar, '1')!== false) {
            $this->setTemplate('Magenest_ShopByBrand::category/index/categories.phtml');
        }
    }
}
