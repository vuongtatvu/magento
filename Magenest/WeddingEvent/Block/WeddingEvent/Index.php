<?php
/**
 * Created by PhpStorm.
 * User: ducanh
 * Date: 24/04/2018
 * Time: 16:00
 */
namespace Magenest\WeddingEvent\Block\WeddingEvent;

use    Magento\Framework\View\Element\Template;

class Index extends Template
{
    protected $_customerSession;

    public function __construct(
        Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->_customerSession = $customerSession;
    }

    public function getGroupId()
    {
        $x = $this->_customerSession->getCustomerGroupId();
        return $x;
    }
    
}