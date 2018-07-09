<?php

namespace Magenest\Sales\Block\Sales;

class Show extends \Magento\Framework\View\Element\Template
{
    
    protected $customerSession;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    )
    {

        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }
    
    public function getProdcutAssign(){

        $objmectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $customer = $this->customerSession->getCustomer();

        if($customer->getIsSalesAgent()){
            $productCollection = $objmectManager->create('\Magento\Catalog\Model\ResourceModel\Product\Collection');
            $collection = $productCollection->addAttributeToSelect('*')->addAttributeToFilter('sale_agent_id', $customer->getEntityId())->load();
            return $collection->getItems();
        }

    }

}
