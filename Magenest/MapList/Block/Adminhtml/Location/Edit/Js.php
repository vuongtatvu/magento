<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 9/23/16
 * Time: 14:34
 */

namespace Magenest\MapList\Block\Adminhtml\Location\Edit;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;


class Js extends \Magento\Backend\Block\Template
{
    protected $_coreRegistry;

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->_scopeConfig = $context->getScopeConfig();
        parent::__construct($context, $data);
    }

    public function getMapApi()
    {
        return $this->_scopeConfig->getValue('maplist/map/api');
    }

    public function getCountry()
    {
        return $this->_scopeConfig->getValue('maplist/map/country');
    }

    public function getZoom()
    {
        return $this->_scopeConfig->getValue('maplist/map/zoom');
    }

    public function getLocation()
    {
        return $this->_coreRegistry->registry('maplist_location_location');
    }

    public function getSelectedProduct()
    {
        $data = $this->_coreRegistry->registry('maplist_location_selected_product');

        $productId = [];
        if (!$data) {
            return $productId;
        }
        foreach ($data as $value) {
            $productId[] = $value['product_id'];
        }

        return $productId;
    }
    public function getLocationIcon(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $mediaUrl = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $model = $this->_coreRegistry->registry('maplist_location_edit');
        if ($model->getData('small_image')!=null) {
            $images = $model->getData('small_image');
                $url = $mediaUrl.'catalog/category/'.$images;
            return $url;
        }else{
            return null;
        }
    }
    public function getLocationGalleryImage(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $mediaUrl = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $model = $this->_coreRegistry->registry('maplist_location_edit');
        if ($model->getData('gallery')!=null) {
            $image=[];
            $images = $model->getData('gallery');
            $image[] = explode(' ; ', $images);
            for ($i = 0; $i < sizeof($image[0])  ; $i++) {
                $url[$i] = $mediaUrl.'catalog/category/'.$image[0][$i];
            }
            return $url;
        }else{
            return null;
        }
    }
}
