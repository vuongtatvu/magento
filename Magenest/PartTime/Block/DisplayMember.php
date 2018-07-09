<?php
namespace Magenest\PartTime\Block;

use    Magento\Framework\View\Element\Template;

class DisplayMember extends Template
{
    private $_memberCollection;
   

    public function __construct(
        Template\Context $context,
        \Magenest\PartTime\Model\ResourceModel\PartTime\CollectionFactory $memberCollection,
    
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->_memberCollection = $memberCollection;
      
    }

    public function getMembers()
    {
        $collection = $this->_memberCollection->create();
        return $collection;
    }
    
}