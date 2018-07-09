<?php
/**
 * Created by PhpStorm.
 * User: duccanh
 * Date: 02/11/2016
 * Time: 15:41
 */
namespace Magenest\ShopByBrand\Block\Group\SideBar;

/**
 * Class Filter
 *
 * @package Magenest\ShopByBrand\Block\Group\SideBar
 */
class Filter extends \Magenest\ShopByBrand\Block\Group\SideBar
{
    /**
     * PrepareTemplate
     */
    public function prepareTemplate()
    {
        $sidebar=$this->_scopeConfig->getValue('shopbybrand/group/sidebar');
        if ($sidebar=='1') {
            $this->setTemplate('Magenest_ShopByBrand::sidebar/group/filter.phtml');
        }
    }

    /**
     * @return bool
     */
}
