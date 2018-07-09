<?php

namespace Magenest\Staff\Block\Staff;

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
    
    public function getId(){
         return $this->customerSession->getCustomerId();
    }
}
