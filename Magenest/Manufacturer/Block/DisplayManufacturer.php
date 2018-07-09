<?php
namespace Magenest\Manufacturer\Block;

use    Magento\Framework\View\Element\Template;

class DisplayManufacturer extends Template
{
    private $_manufacturerCollection;


    public function __construct(
        Template\Context $context,
        \Magenest\Manufacturer\Model\ResourceModel\Manufacturer\CollectionFactory $manufacturerCollection,

        array $data = [])
    {
        parent::__construct($context, $data);
        $this->_manufacturerCollection = $manufacturerCollection;

    }

    public function getManufacturer()
    {
        $collection = $this->_manufacturerCollection->create();
        return $collection;
    }
}