<?php

namespace Magenest\ShopByBrand\Observer\Brand;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 *  Class EditBrand
 */
class EditBrand implements ObserverInterface
{
    /**
     * @var \Magenest\ShopByBrand\Model\Brand
     */
    protected $_brand;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @param \Magenest\ShopByBrand\Model\Brand $brand
     * @param \Psr\Log\LoggerInterface          $logger
     * @param ManagerInterface                  $messageManager
     * @param ScopeConfigInterface              $scopeConfig
     */
    public function __construct(
        \Magenest\ShopByBrand\Model\Brand $brand,
        \Psr\Log\LoggerInterface $logger,
        ManagerInterface $messageManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->_brand         = $brand;
        $this->_logger        = $logger;
        $this->messageManager = $messageManager;
        $this->_scopeConfig   = $scopeConfig;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(Observer $observer)
    {
        $product = $observer->getEvent()->getData('product');
        $brandId = $this->_brand->getCollection()
            ->addProductToFilter($product->getId())
            ->getLastItem()
            ->getData('brand_id');
        if (isset($brandId)) {
            $product->setData('brand_id', $brandId);
        } else {
            $product->setData('brand_id', 0);
        }
    }
}
