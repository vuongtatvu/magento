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
namespace Magenest\ShopByBrand\Helper;

use function count;
use Magento\Catalog\Model\Product;
use Magenest\ShopByBrand\Model\Brand as BrandModel;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magenest\ShopByBrand\Model\Config\Router;
use Magento\Framework\App\Config\ScopeConfigInterface;
use const true;

/**
 * Class Brand
 *
 * @package Magenest\ShopByBrand\Helper
 */
class Brand
{
    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $_product;

    /**
     * @var \Magenest\ShopByBrand\Model\Brand
     */
    protected $_brand;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlBuilder;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var array
     */
    protected $data=[];


    /**
     * @param BrandModel            $brand
     * @param UrlInterface          $urlBuilder
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        BrandModel $brand,
        UrlInterface $urlBuilder,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->_storeManager = $storeManager;
        $this->_brand        = $brand;
        $this->_urlBuilder   = $urlBuilder;
        $this->_scopeConfig  = $scopeConfig;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $data
     * @return Brand
     */
    public function setData($data)
    {
         $this->data = $data;
        return $this;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return Brand
     */
    public function setProduct(Product $product)
    {
        $this->_product = $product;

        return $this;
    }

    /**
     * @return bool
     */
    public function isShowBrand()
    {
        $mode=$this->_scopeConfig->getValue('shopbybrand/product/show');
        if ($mode==0) {
            return false;
        }
        $storeId = $this->_storeManager->getStore()->getId();
        $brand   = $this->_brand->getCollection()
            ->addActiveFilter()
            ->addStoreToFilter($storeId)
            ->addProductToFilter($this->_product->getId())
            ->getLastItem()
            ->getData();

        if (isset($brand['brand_id'])) {
            $this->data = $brand;
            return true;
        }

        return false;
    }
    /**
     * Get all store id
     *
     * @return array
     */
    public function getBrandAllStoreView()
    {
        $arrayStoreId=$this->getAllStoreId();
        $sumStore=count($arrayStoreId)-1;
        $arrayBrand=[];
        $allBand=$this->getAllBrand();

        foreach ($allBand as $brand) {
            $dem=0;
            foreach ($arrayStoreId as $storeId) {
                $temp  = $this->_brand->getCollection()
                    ->addActiveFilter()
                    ->addStoreAllToFilter($storeId, $brand['brand_id'])
                    ->getData();
                if (count($temp)) {
                    $dem=$dem+1;
                }
            }
            if ($dem==$sumStore) {
                $arrayBrand[]=$brand;
            }
        }
        return $arrayBrand;
    }
    /**
     * @return array
     */
    public function getAllBrand()
    {
        return $this->_brand->getCollection()
            ->getData();
    }
    public function getAllStoreId()
    {
        $allStoreId = array();

        $allStore = $this->_storeManager->getStores($withDefault = false);

        foreach ($allStore as $store) {
            $allStoreId[] = $store->getStoreId();
        }

        return $allStoreId;
    }
    /**
     * @return array
     */
    public function getBrand()
    {
        $configUrl = $this->getUrlRewrite();
        $data = [];
        if (isset($this->data)) {
            $logo = $this->data['logo'];
            $baseUrl      = $this->_urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]);
            if ($logo=="") {
                $baseUrl = $this->_urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_STATIC]);
                $data['logo']=$baseUrl.Router::ROUTER_STATIC;
            } else {
                $data['logo'] = $baseUrl.Router::ROUTER_MEDIA.$this->data['logo'];
            }
            $data['name'] = $this->data['name'];
            $data['url']  = $configUrl.'/'.$this->data['url_key'];
        }

        return $data;
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

    /**
     * @return bool
     */
    public function isShowRelate()
    {
        $relate = $this->_product->getData('brand_related');
        if (isset($relate)) {
            if ($relate == 1) {
                return true;
            }

            return false;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function isShowStore()
    {
        $storeId = $this->_storeManager->getStore()->getId();
        $brand   = $this->_brand->getCollection()
            ->addActiveFilter()
            ->addStoreToFilter($storeId);
        return $brand;
    }
    /**
     * @return array
     */
    public function getIdRelateProduct()
    {
        $brand      = $this->_brand->getCollection()
            ->addActiveFilter()
            ->addProductToFilter($this->_product->getId())
            ->getLastItem();
        $id         = $brand->getData('brand_id');
        $collection = $this->_brand->getCollection()
            ->addBrandIdToFilter($id)
            ->getData();
        $data       = array();
        foreach ($collection as $row) {
            $data[] = $row['product_id'];
        }

        return $data;
    }
}
