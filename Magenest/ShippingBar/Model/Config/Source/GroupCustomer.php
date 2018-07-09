<?php
namespace Magenest\ShippingBar\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class GroupCustomer extends AbstractSource
{
    protected $groupCollectionFactory;
    protected $_options;
    public function __construct(
        \Magento\Customer\Model\ResourceModel\Group\CollectionFactory  $groupCollectionFactory
    ) {
        $this->groupCollectionFactory = $groupCollectionFactory;
    }


    public function getAllOptions($addEmpty = true)
    {

        $collection = $this -> groupCollectionFactory
            ->create()
            ->load();
        $_options = [];
        if($addEmpty){
            $_options[] = ['label' => __('ALL GROUPS'), 'value' => '0'];
        }
        foreach ($collection as $group) {
            $_options[] = ['label' => $group->getCustomerGroupCode(), 'value' => $group->getCustomerGroupId()];
        }

        return $_options;
    }
}