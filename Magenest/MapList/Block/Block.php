<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 9/21/16
 * Time: 08:35
 */

namespace Magenest\MapList\Block;

class Block extends \Magento\Framework\View\Element\Template
{
    protected $_coreRegistry;

    protected $_scopeConfig;

    protected $_mapFactory;

    protected $_mapLocationFactory;

    protected $_categoryFactory;

    protected $_locationCategoryFactory;

    protected $_country;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magenest\MapList\Model\LocationFactory $locationFactory,
        \Magenest\MapList\Model\MapFactory $mapFactory,
        \Magenest\MapList\Model\MapLocationFactory $mapLocationFactory,
        \Magenest\MapList\Model\LocationCategoryFactory $locationCategoryFactory,
        \Magenest\MapList\Model\CategoryFactory $categoryFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Directory\Model\Config\Source\Country $country

    ) {
        parent::__construct($context);
        $this->_coreRegistry = $registry;
        $this->_mapFactory = $mapFactory;
        $this->_mapLocationFactory = $mapLocationFactory;
        $this->_locationCategoryFactory = $locationCategoryFactory;
        $this->_categoryFactory = $categoryFactory;
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_country = $country;
    }

    public function getConfig()
    {
        $dataConfig = [];
        $dataConfig['countryCode'] = $this->_scopeConfig->getValue('maplist/map/country');
        $dataConfig['mapApi'] = $this->_scopeConfig->getValue('maplist/map/api');
        $dataConfig['zoom'] = $this->_scopeConfig->getValue('maplist/map/zoom');
        $dataConfig['unit'] = $this->_scopeConfig->getValue('maplist/map/unit');
        $dataConfig['travel_mode'] = $this->_scopeConfig->getValue('maplist/map/travel_mode');
        $dataConfig['max_distance'] = $this->_scopeConfig->getValue('maplist/map/max_distance');
        $dataConfig['default_distance'] = $this->_scopeConfig->getValue('maplist/map/default_distance');

        return $dataConfig;
    }

    protected function getImageUrl($imageData)
    {
        $mediaDirectory = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $image = $imageData;
        if (!$image) {
            return "http://www.mkshahmcrc.org/images/site_img/user.png";
        }

        return $mediaDirectory . 'catalog/category/' . $image;
    }

    protected function getGalleryImageUrl($imageData)
    {
        $mediaDirectory = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $image = $imageData;
        if (!$image) {
            return "https://www.magearray.com/pub/media/catalog/product/cache/1/image/e9c3970ab036de70892d86c6d221abfe/m/a/magearray-storelocator-icon.png";
        }

        return $mediaDirectory . 'catalog/category/' . $image;
    }

}
