<?php
/**
 * Created by PhpStorm.
 * User: duccanh
 * Date: 02/11/2016
 * Time: 16:03
 */
namespace Magenest\ShopByBrand\Block\Group\View;

/**
 * Class Listing
 *
 * @package Magenest\ShopByBrand\Block\Group\View
 */
class Listing extends \Magenest\ShopByBrand\Block\Group\Listing
{
    public function prepareTemplate()
    {
        $this->setTemplate('Magenest_ShopByBrand::sidebar/group/view.phtml');
    }
}
