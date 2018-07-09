<?php
/**
 * Created by PhpStorm.
 * User: duccanh
 * Date: 02/11/2016
 * Time: 15:48
 */
namespace Magenest\ShopByBrand\Block\Group;

use Magento\Framework\UrlInterface;
use Magenest\ShopByBrand\Model\Config\Router;

/**
 * Class SideBar
 *
 * @package Magenest\ShopByBrand\Block\Group
 */
class SideBar extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magenest\ShopByBrand\Model\GroupFactory
     */
    protected $_group;

    /**
     * @var \Magenest\ShopByBrand\Model\BrandFactory
     */
    protected $_brand;

    /**
     * SideBar constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magenest\ShopByBrand\Model\GroupFactory         $groupFactory
     * @param \Magenest\ShopByBrand\Model\Brand                $brandFactory
     * @param array                                            $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magenest\ShopByBrand\Model\GroupFactory $groupFactory,
        \Magenest\ShopByBrand\Model\Brand $brandFactory,
        array $data = []
    ) {
        $this->_brand        = $brandFactory;
        $this->_group        = $groupFactory;
        parent::__construct($context, $data);
        $this->prepareTemplate();
    }

    /**
     * Return template
     */
    public function prepareTemplate()
    {
    }
    /**
     * @return array
     */
    public function getGroupInfo()
    {
        $data = $this->_group->create()->getCollection()
        //            ->addActiveFilter()
            ->addFieldToFilter('status', 1)
            ->setOrder('name', 'ASC')
            ->getData();
        return $data;
    }

    /**
     * Get Qty Brand
     *
     * @param  $groupId
     * @return int
     */
    public function getQtyBrand($groupId)
    {
        $storeId = $this->_storeManager->getStore()->getId();
        $collection = $this->_brand->getCollection()
            ->setOrder('sort_order', 'ASC')
            ->setOrder('name', 'ASC')
            ->addStoreToFilter($storeId)
            ->addFieldToFilter('status', 1)
            ->addFieldToFilter('groups', array(array('like' => '%'.$groupId.'%'),array('like' => '%0%')))
            ->getData();
        return count($collection);
    }

    /**
     * @return string
     */
    public function getBaseGroupUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl().Router::ROUTER_GROUP;
    }
}
