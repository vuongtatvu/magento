<?php

namespace Magenest\Vu\Block\Vendor;

class Index extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $dateTime;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;
    
    protected $vendorFactory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\Customer\Model\Session $customerSession,
        \Magenest\Vu\Model\VendorFactory $vendorFactory,
        array $data = []
    )
    {
        $this->dateTime = $dateTime;
        $this->customerSession = $customerSession;
        $this->vendorFactory = $vendorFactory;
        parent::__construct($context, $data);
    }


    public function getVendors()
    {
        return $this->vendorFactory
            ->create()
            ->getCollection()
            ->addFieldToFilter('customer_id', $this->customerSession->getCustomerId())
            ->getFirstItem();
    }

    public function getCustomerId()
    {
        return $this->customerSession->getCustomerId();
    }


}
