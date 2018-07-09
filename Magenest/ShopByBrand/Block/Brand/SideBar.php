<?php
/**
 * Created by PhpStorm.
 * User: duccanh
 * Date: 09/06/2016
 * Time: 11:51
 */
namespace Magenest\ShopByBrand\Block\Brand;

use Magento\Framework\UrlInterface;
use Magenest\ShopByBrand\Model\Config\Router;
use function print_r;
use const true;

/**
 * Class SideBar
 *
 * @package Magenest\ShopByBrand\Block\Brand
 */
class SideBar extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magenest\ShopByBrand\Model\BrandFactory
     */
    protected $_brand;


    /**
     * SideBar constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magenest\ShopByBrand\Model\BrandFactory         $brand
     * @param array                                            $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magenest\ShopByBrand\Model\BrandFactory $brand,
        array $data = []
    ) {
        $this->_brand        = $brand;
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
    public function getBrandInfo()
    {
        $storeId = $this->_storeManager->getStore()->getId();
        $data = $this->_brand->create()->getCollection()
            ->addActiveFilter()
            ->addFieldToFilter('status', 1)
            ->addStoreToFilter($storeId)
            ->setOrder('sort_order', 'ASC')
            ->setOrder('name', 'ASC')
            ->getData();
        $this->_logger->log(100, print_r($data, true));
        return $data;
    }
    /**
     * @return string
     */
    public function getBaseImageUrl()
    {
        $baseUrl = $this->_urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]);
        return $baseUrl.Router::ROUTER_MEDIA;
    }

    /**
     * @return string
     */
    public function getBaseBrandUrl()
    {
        $configUrl = $this->getUrlRewrite();
        return $this->_storeManager->getStore()->getBaseUrl().$configUrl;
    }

    /**
     * Get Url Rewrite
     *
     * @return mixed
     */
    public function getUrlRewrite()
    {
        $value = $this->_scopeConfig->getValue('shopbybrand/page/url');
        return $value;
    }
    public function getImage($brand)
    {
        $baseUrl = $this->_urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]);
        $brand['logo']=$baseUrl."shopbybrand/brand/image".$brand['logo'];
        $ch = curl_init($brand['logo']);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($retcode==200) {
        } else {
            $brand['logo'] =$this->getViewFileUrl('Magento_Catalog::images/product/placeholder/thumbnail.jpg');
        }
        return $brand['logo'];
    }
}
