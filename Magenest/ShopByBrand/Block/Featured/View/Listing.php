<?php
/**
 * Created by PhpStorm.
 * User: duccanh
 * Date: 26/10/2016
 * Time: 09:00
 */
namespace Magenest\ShopByBrand\Block\Featured\View;

use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Framework\UrlInterface;
use Magenest\ShopByBrand\Model\Config\Router;
use Magento\Catalog\Model\ProductFactory as Product;

/**
 * Class Listing
 *
 * @package Magenest\ShopByBrand\Block\Featured\View
 */
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
     * @var Product
     */
    protected $_product;


    /**
     * @var AbstractProduct
     */
    protected $_absProduct;
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
        Product $productFactory,
        AbstractProduct $abstractProduct,
        array $data = []
    ) {
        $this->_absProduct = $abstractProduct;
        $this->_product      = $productFactory;
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

    /**
     * @return mixed
     */
    public function getFeaturedConfig()
    {
        return $this->_scopeConfig->getValue('shopbybrand/brandpage/featured_product');
    }

    /**
     * @return \Magenest\ShopByBrand\Model\Config\Router
     */
    public function getRouter()
    {
        return $this->router;
    }


    /**
     * @return array
     */
    public function getListId()
    {
        $id         = $this->getRequest()->getParam('brand_id');
        $collection = $this->brand->getCollection()
            ->addProductFeatured($id)
            ->getData();
        $data       = array();
        foreach ($collection as $row) {
            $data[] = $row['product_id'];
        }

        return $data;
    }

    public function getItems($productId)
    {
        return $this->_product->create()->load($productId);
    }

    /**
     * Get Product Data
     *
     * @param $productId
     *
     * @return array
     */
    public function getProduct($productId)
    {
        $datas = $this->_product->create()->getCollection()
            ->addFieldToFilter('entity_id', $productId)
            ->addAttributeToSelect('*');

        return $datas;
    }

    /**
     * @param $product
     * @param $param
     * @return \Magento\Catalog\Block\Product\Image
     */
    public function getProductImage($product, $param)
    {
        $img = $this->_absProduct->getImage($product, $param);

        return $img;
    }

    /**
     * Get Product Name Vendor
     *
     * @param $productId
     *
     * @return mixed
     */
    public function getNameProduct($productId)
    {
        $productName = '';

        $params = $this->getProduct($productId);

        foreach ($params as $param) {
            $productName = $param->getName();
        }

        return $productName;
    }

    /**
     * Get Price Product Vendor
     *
     * @param $productId
     *
     * @return mixed
     */
    public function getPriceProduct($productId)
    {
        $priceProduct = 0;

        $params = $this->getProduct($productId);

        foreach ($params as $param) {
            $priceProduct = $param->getFinalPrice();
        }

        return $priceProduct;
    }
}
