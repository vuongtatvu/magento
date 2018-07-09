<?php
namespace Magenest\ShopByBrand\Plugin;

use Magenest\ShopByBrand\Helper\Brand as BrandHelper;
use Magenest\ShopByBrand\Model\Config\Router;
use function print_r;
use const true;

/**
 * Class TopMenu
 *
 * @package Magenest\ShopByBrand\Plugin
 */
class TopMenu extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_template = "Magenest_ShopByBrand::menu/menu.phtml";

    /**
     * @var \Magenest\ShopByBrand\Model\Brand
     */
    protected $brand;

    /**
     * @var string
     */
    protected $html="";
    protected $_brandHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magenest\ShopByBrand\Model\Brand                $brand
     * @param array                                            $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magenest\ShopByBrand\Model\Brand  $brand,
        BrandHelper $brandHelper,
        array $data = []
    ) {

        parent::__construct($context, $data);
        $this->brand = $brand;
        $this->_brandHelper=$brandHelper;
        $this->prepareHtml();
    }

    /**
     * @return mixed
     */

    public function getCollection()
    {

        return $this->brand->getCollection()
            ->addActiveFilter();
    }
    public function getCurrentStore()
    {
        return $this->_storeManager->getStore()->getId();
    }
    /**
     * Prepare Html
     */
    public function prepareHtml()
    {
        $show = $this->_scopeConfig->getValue('shopbybrand/general/enabled');
        if ($show == '1') {
            $this->html = $this->_toHtml();
        } else {
            $this->html = '';
        }
    }
    /**
     * @return bool
     */
    public function isShowBrand()
    {
        $data=$this->_brandHelper->isShowStore();
        return $data;
    }

    public function getRouterBrand()
    {
        return Router::ROUTER_BRAND;
    }

    /**
     * @param $subject
     * @param $result
     * @return string
     */
    public function afterGetHtml($subject, $result)
    {
        return $result.$this->html;
    }

    /**
     * @return string
     */
    public function getBrandUrl()
    {
        $configUrl = $this->_scopeConfig->getValue('shopbybrand/page/url');
        return $this->getBaseUrl().$configUrl;
    }
}
