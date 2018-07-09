<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 10/12/16
 * Time: 11:51
 */

namespace Magenest\MapList\Block\Catalog;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class LocationCatalog extends Template
{
    protected $_locationProductFactory;

    protected $_coreRegistry;

    public function __construct(
        \Magenest\MapList\Model\LocationProductFactory $locationProductFactory,
        Registry $registry,
        Context $context,
        array $data
    ) {
        $this->_coreRegistry = $registry;
        $this->_locationProductFactory = $locationProductFactory;
        parent::__construct($context, $data);
    }

    public function getDataView()
    {
        $data = $this->_coreRegistry->registry('current_product');
        $productId = $data->getId();
        $locationData = $this->_locationProductFactory->create()
            ->getCollection()
            ->join(
                ['cp_table' => 'magenest_maplist_location'],
                'main_table.location_id = cp_table.location_id'
            )
            ->addFieldToFilter('product_id', $productId)
            ->addFieldToFilter('is_active', 1)
            ->getData();
        foreach ($locationData as $key => $value) {
            $locationData[$key]['small_image_url'] = $this->getImageUrl($value['small_image']);
        }

        //var_dump($locationData);die();
        return $locationData;
    }

    public function getConfig()
    {
        $dataConfig = [];
        $dataConfig['mapApi'] = $this->_scopeConfig->getValue('maplist/map/api');
        $dataConfig['unit'] = $this->_scopeConfig->getValue('maplist/map/unit');
        $dataConfig['country'] = $this->_scopeConfig->getValue('maplist/map/country');
        $dataConfig['zoom'] = $this->_scopeConfig->getValue('maplist/map/zoom');
        $dataConfig['travel_mode'] = $this->_scopeConfig->getValue('maplist/map/travel_mode');

        return $dataConfig;
    }

    protected function getImageUrl($imageData)
    {
        $mediaDirectory = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $image = unserialize($imageData);

        if (!$image) {
            return "//";
        }

        return $mediaDirectory . 'maplist/location/icon' . $image['file'];
    }
}
