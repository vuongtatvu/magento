<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 19/01/2018
 * Time: 09:27
 */

namespace Magenest\ShopByBrand\Block\Adminhtml\Brand;

use Magento\Backend\Block\Template;

class Validate extends Template
{
    /**
     * @var
     */
    protected $brandFactory;

    public function __construct(
        Template\Context $context,
        array $data = [],
        \Magenest\ShopByBrand\Model\BrandFactory $brandFactory
    )
    {
        parent::__construct($context, $data);
        $this->brandFactory = $brandFactory;
    }


    public function getBanner()
    {
        $id    = $this->getRequest()->getParam('brand_id');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $brand = $objectManager->create('Magenest\ShopByBrand\Model\Brand')->load($id);
        $storeManager = $objectManager->get(\Magento\Store\Model\StoreManagerInterface::class);
        $mediaUrl = $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        if($brand->getBanner()=='') return null;
        return $mediaUrl."shopbybrand/brand/image".$brand->getBanner();
    }

    public function getLogo()
    {
        $id    = $this->getRequest()->getParam('brand_id');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $brand = $objectManager->create('Magenest\ShopByBrand\Model\Brand')->load($id);
        $storeManager = $objectManager->get(\Magento\Store\Model\StoreManagerInterface::class);
        $mediaUrl = $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        if($brand->getLogo()=='') return null;
        return $mediaUrl."shopbybrand/brand/image".$brand->getLogo();
    }

}
