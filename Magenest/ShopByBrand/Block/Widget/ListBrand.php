<?php
namespace Magenest\ShopByBrand\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Magento\Framework\UrlInterface;
use Magenest\ShopByBrand\Model\Config\Router;
use function print_r;
use const true;

class ListBrand extends Template implements BlockInterface
{
    protected $_template = "widget/listbrand.phtml";

    protected $_brand;

    public function __construct(
        Template\Context $context,
        \Magenest\ShopByBrand\Model\BrandFactory $brand,
        array $data)
    {
        $this->_brand = $brand;
        parent::__construct($context, $data);
    }

    public function getBrands()
    {
        $number = $this->getData('number_of_brand');
        $orderby = $this->getSortBy();

        $brandCollection = $this->_brand->create()->getCollection()
            ->addFieldToSelect('*')
            ->setOrder('name', $orderby)
            ->setPageSize($number)
            ->getData();
        return $brandCollection;
    }

    public function getBaseImageUrl()
    {
        $baseUrl = $this->_urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]);
        return $baseUrl . Router::ROUTER_MEDIA;
    }

    /**
     * @return string
     */
    public function getBaseBrandUrl()
    {
        $configUrl = $this->getUrlRewrite();
        return $this->_storeManager->getStore()->getBaseUrl() . $configUrl;
    }

    public function getUrlRewrite()
    {
        $value = $this->_scopeConfig->getValue('shopbybrand/page/url');
        return $value;
    }

    public function getImage($brand)
    {
        $baseUrl = $this->_urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]);
        $brand['logo'] = $baseUrl . "shopbybrand/brand/image" . $brand['logo'];
        $ch = curl_init($brand['logo']);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($retcode == 200) {
        } else {
            $brand['logo'] = $this->getViewFileUrl('Magento_Catalog::images/product/placeholder/thumbnail.jpg');
        }
        return $brand['logo'];
    }

    public function getSortBy()
    {
        if (!$this->hasData('brand_sort_by')) {
            $this->setData('brand_sort_by', 'asc');
        }
        return $this->getData('brand_sort_by');
    }

}