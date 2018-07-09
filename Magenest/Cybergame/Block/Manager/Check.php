<?php

namespace Magenest\Cybergame\Block\Manager;

class Check extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $dateTime;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    )
    {
        $this->dateTime = $dateTime;
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    public function getIsCheck()
    {
        $objmectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $customerid = $this->customerSession->getCustomerId();

        $customerCollection = $objmectManager->create('Magento\Customer\Model\Customer')->load($customerid);

        if ($customerCollection['is_manager'] == 1) {
            return true;
        }
        return false;
    }

    public function getRoom()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productCollection = $objectManager->create('Magenest\Cybergame\Model\ResourceModel\RoomExtraOption\Collection');
        $collection = $productCollection->load();
        return $collection;
    }

    public function getRoomProductInfo()
    {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $registry = $objectManager->get('\Magento\Framework\Registry');

        $currentProduct = $registry->registry('current_product');

        $product_id = $currentProduct->getEntityId();

        $productCollection = $objectManager->create('\Magenest\Cybergame\Model\ResourceModel\RoomExtraOption\Collection');

        $collection = $productCollection->load('product_id', $product_id);

        $product = $collection->getItems();
        foreach ($product as $item) {
            return $item;
        }
    }

    public function getCurrentProduct(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $registry = $objectManager->get('\Magento\Framework\Registry');

        return $registry->registry('current_product');
    }

}
