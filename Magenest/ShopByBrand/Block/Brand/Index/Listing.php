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
namespace Magenest\ShopByBrand\Block\Brand\Index;

use Magento\Framework\UrlInterface;
use Magenest\ShopByBrand\Model\Config\Router;

class Listing extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magenest\ShopByBrand\Model\Brand
     */
    protected $brand;

    /**
     * @var \Magenest\ShopByBrand\Model\Config\Router
     */
    protected $router;

    /**
     * Listing constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magenest\ShopByBrand\Model\Brand                $brand
     * @param Router                                           $router
     * @param array                                            $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magenest\ShopByBrand\Model\Brand $brand,
        \Magenest\ShopByBrand\Model\Config\Router $router,
        array $data = []
    ) {
        $this->brand         = $brand;
        $this->router        = $router;
        parent::__construct($context, $data);
        $this->prepareTemplate();
    }
    /**
     * setting
     */
    public function prepareTemplate()
    {
    }

    /**
     * @return $this
     */
    public function _prepareLayout()
    {
        
        $page_title = $this->_scopeConfig->getValue('shopbybrand/page/title');
        $meta_keywords = $this->_scopeConfig->getValue('shopbybrand/page/keywords');
        $meta_description = $this->_scopeConfig->getValue('shopbybrand/page/description');

        if ($page_title) {
            $this->pageConfig->getTitle()->set(__($page_title));
        }

        if ($meta_keywords) {
            $this->pageConfig->setKeywords($meta_keywords);
        }

        if ($meta_description) {
            $this->pageConfig->setDescription($meta_description);
        }

        return parent::_prepareLayout();
    }
    
    /**
     * @return array
     */
    public function getAllBrand()
    {
        $storeId = $this->_storeManager->getStore()->getId();
        return $this->brand->getCollection()
            ->setOrder('sort_order', 'ASC')
            ->setOrder('name', 'ASC')
            ->addStoreToFilter($storeId)
            ->addFieldToFilter('status', 1)
            ->getData();
    }

    /**
     * params $data
     *
     * return first word brands
     */
    public function getBrandsStyle($data)
    {
        return strtoupper(substr($data, 0, 1));
    }

    /**
     * @param $key
     * @return bool
     */
    public function checkFirstBrand($key)
    {
        $datas = $this->getAllBrand();
        foreach ($datas as $data) {
            $first = $this->getBrandsStyle($data['name']);
            if ($first == $key) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public function getScopeConfig()
    {
        return $this->_scopeConfig;
    }

    /**
     * @return \Magento\Framework\UrlInterface
     */
    public function getUrlBuilder()
    {
        return $this->_urlBuilder;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    /**
     * @return string
     */
    public function getBaseMediaUrl()
    {
        return $this->_urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]);
    }

    public function getBaseStaticUrl()
    {
        return $this->_urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_STATIC]);
    }

    /**
     * @return mixed
     */
    public function getBrandConfig()
    {
        return $this->_scopeConfig->getValue('shopbybrand/list/show');
    }

    /**
     * @return \Magenest\ShopByBrand\Model\Config\Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @return string
     */
    public function getBrandUrl()
    {
        $configUrl = $this->getUrlRewrite();
        return $this->getBaseUrl().$configUrl.'/';
    }
    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->getBaseMediaUrl().Router::ROUTER_MEDIA.'/';
    }

    /**
     * @param $brand
     * @return string
     */
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
}
